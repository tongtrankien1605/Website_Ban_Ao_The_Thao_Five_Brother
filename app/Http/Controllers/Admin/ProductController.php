<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAtribute;
use App\Models\ProductAtributeValue;
use App\Models\ProductImage;
use App\Models\Skus;
use App\Models\Variant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::select(
            [
                'products.*',
                'categories.name as product_category',
                'brands.name as product_brand',
                DB::raw('(SELECT image_url FROM product_images WHERE product_images.id_product = products.id ORDER BY updated_at DESC LIMIT 1) as product_image')
            ]
        )
            ->join('categories', function ($q) {
                $q->on('categories.id', '=', 'products.id_category');
                $q->whereNull('categories.deleted_at');
            })
            ->join('brands', function ($q) {
                $q->on('brands.id', '=', 'products.id_brand');
                $q->whereNull('brands.deleted_at');
            })
            ->orderByDesc('products.updated_at')->paginate(20);
        return view('admin.products.index', compact(['products']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = ProductAtribute::get()->pluck('name', 'id');
        // dd($attributes);
        $attributeValues = ProductAtributeValue::whereIn('product_attribute_id', $attributes->keys())
            ->get()
            ->groupBy('product_attribute_id');
        $brands = Brand::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        return view('admin.products.create', compact(['brands', 'categories', 'attributes', 'attributeValues']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'id_category' => 'required|integer|exists:categories,id',
        'id_brand' => 'required|integer|exists:brands,id',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        'variants.*.name' => 'required|string|max:255',
        'variants.*.price' => 'required|numeric|min:0',
        'variants.*.sale_price' => 'nullable|numeric|min:0',
        'variants.*.barcode' => 'required|string|unique:skuses,barcode',
        'variants.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'variants.*.attributes.*.attribute_id' => 'integer|exists:product_attributes,id',
        'variants.*.attributes.*.attribute_value_id' => 'integer|exists:product_attribute_values,id',
    ]);

    try {
        DB::beginTransaction();


        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'id_category' => $request->id_category,
            'id_brand' => $request->id_brand
        ]);

        if ($request->has('images')) {
            $productImages = [];
            foreach ($request->file('images') as $image) {
                $productImages[] = [
                    'id_product' => $product->id,
                    'image_url' => str_replace('public/', '', $image->store('public/products')),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            ProductImage::insert($productImages);
        }
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $skuImagePath = isset($variant['image']) ? $variant['image']->store('public/productsVariants') : null;

                $sku = Skus::create([
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'sale_price' => $variant['sale_price'] ?? null,
                    'barcode' => $product->id . $variant['barcode'],
                    'image' => $skuImagePath ? str_replace('public/', '', $skuImagePath) : null,
                ]);


                if (isset($variant['attributes']) && is_array($variant['attributes'])) {
                    foreach ($variant['attributes'] as $attr) {
                        Variant::create([
                            'product_id' => $product->id,
                            'id_skus' => $sku->id,
                            'product_attribute_id' => $attr['attribute_id'],
                            'product_attribute_value_id' => $attr['attribute_value_id']
                        ]);
                    }
                }
            }
        }

        DB::commit();
        return redirect()->route('admin.product.index')->with('status_succeed', 'Thêm sản phẩm thành công.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors('Lỗi: ' . $e->getMessage())->withInput();
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $brand = Brand::whereNull('deleted_at')->where('id', $product->id_brand)->first();
        $category = Category::whereNull('deleted_at')->where('id', $product->id_category)->first();
        $productImages = ProductImage::whereNull('deleted_at')->where('id_product', $product->id)->get();
        return view('admin.products.show', compact(['brand', 'category', 'product', 'productImages']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $attributes = ProductAtribute::get()->pluck('name', 'id');
        $attributeValues = ProductAtributeValue::whereIn('product_attribute_id', $attributes->keys())
            ->get()
            ->groupBy('product_attribute_id');
        $brands = Brand::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        $productImages = ProductImage::whereNull('deleted_at')->where('id_product', $product->id)->get();
        $skues = Skus::whereNull('deleted_at')->where('product_id', $product->id)->get();
        return view('admin.products.edit', compact(['brands', 'categories', 'product', 'attributes', 'attributeValues', 'productImages', 'skues']));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Product $product)
    {
        try {

            DB::beginTransaction();

            $product->name = $request->name;
            $product->description = $request->description;
            $product->id_category = $request->id_category;
            $product->id_brand = $request->id_brand;
            $product->status = 1;
            $product->save();
            $productImages = ProductImage::where('id_product', $product->id)->get();

            if ($productImages) {
                foreach ($productImages as $productImage) {
                    Storage::delete('public/' . $productImage->image_url);
                }
                ProductImage::where('id_product', $product->id)->delete();
            }
            if ($request->images) {
                $productImages = [];
                foreach ($request->images as $image) {
                    $productImagePath = $image->store('public/products');
                    $productImages[] = [
                        'id_product' => $product->id,
                        'image_url' => str_replace('public/', '', $productImagePath),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
                ProductImage::insert($productImages);
            }
            $existingBarcodes = Skus::where('product_id', $product->id)->pluck('barcode')->toArray();
            $skusToInsert = [];
            $skusToUpdate = [];

            if (!empty($request->variants)) {
                foreach ($request->variants as $variant) {
                    if (!isset($variant['barcode'])) continue;
                    if (in_array($variant['barcode'], $existingBarcodes)) {
                        // Cập nhật biến thể cũ
                        $skusToUpdate[] = [
                            'barcode' => $variant['barcode'],
                            'name' => $variant['name'],
                            'price' => $variant['price'],
                            'sale_price' => $variant['sale_price'],
                            'updated_at' => Carbon::now(),
                        ];

                        // Nếu có ảnh mới, cập nhật ảnh
                        if (isset($variant['image']) && $variant['image']->isValid()) {
                            $skuImagePath = $variant['image']->store('public/productsVariants');
                            Skus::where('barcode', $variant['barcode'])->update([
                                'image' => str_replace('public/', '', $skuImagePath)
                            ]);
                        }
                    } else {
                        // Thêm biến thể mới
                        $skuImagePath = isset($variant['image']) && $variant['image']->isValid()
                            ? str_replace('public/', '', $variant['image']->store('public/productsVariants'))
                            : null;

                        $skusToInsert[] = [
                            'product_id' => $product->id,
                            'name' => $variant['name'],
                            'price' => $variant['price'],
                            'sale_price' => $variant['sale_price'],
                            'barcode' => $variant['barcode'],
                            'image' => $skuImagePath,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }
                }
            }

            // Cập nhật biến thể cũ
            foreach ($skusToUpdate as $skuData) {
                Skus::where('barcode', $skuData['barcode'])->update([
                    'name' => $skuData['name'],
                    'price' => $skuData['price'],
                    'sale_price' => $skuData['sale_price'],
                    'updated_at' => Carbon::now(),
                ]);
            }

            // Thêm biến thể mới nếu có
            if (!empty($skusToInsert)) {
                Skus::insert($skusToInsert);
            }

            // Xóa các biến thể không có trong request (tức là bị xóa trên giao diện)
            $requestBarcodes = array_column($request->variants, 'barcode');
            Skus::where('product_id', $product->id)
                ->whereNotIn('barcode', $requestBarcodes)
                ->update(['status' => 0]);

            DB::commit();

            return redirect()->route('admin.product.index')->with('message', 'Sửa sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->update(['status' => 0]);
        Skus::where('product_id', $product->id)->update(['status' => 0]);
        return redirect()->route('admin.product.index');
    }
}
