<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAtribute;
use App\Models\ProductAtributeValue;
use App\Models\ProductImage;
use App\Models\Skus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::leftJoin('skuses', function ($q) {
            $q->on('skuses.product_id', '=', 'products.id');
            $q->whereNull('skuses.deleted_at');
        })->join('categories', function ($q) {
            $q->on('categories.id', '=', 'products.id_category');
            $q->whereNull('categories.deleted_at');
        })
            ->join('brands', function ($q) {
                $q->on('brands.id', '=', 'products.id_brand');
                $q->whereNull('brands.deleted_at');
            })->select(
                [
                    'products.*',
                    'categories.name as product_category',
                    'brands.name as product_brand',
                    DB::raw('COUNT(skuses.status) as count_variant'),
                ]
            )->groupBy([
                'products.id',
                'products.name',
                'products.id_category',
                'products.id_brand',
                'products.description',
                'products.image',
                'products.status',
                'products.created_at',
                'products.updated_at',
                'products.deleted_at',
                'categories.name',
                'brands.name'
            ])->orderByDesc('products.updated_at')->paginate(20);
        return view('admin.products.index', compact(['products']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = ProductAtribute::get()->pluck('name', 'id');
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
    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->id_category = $request->id_category;
            $product->id_brand = $request->id_brand;
            $productpath = $request->image->store('public/products');
            $product->image = str_replace('public/', '', $productpath);
            $product->save();
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
            if (!empty($request->variants)) {
                $skues = [];
                foreach ($request->variants as $variant) {
                    $skuImages = $variant['image']->store('public/productsVariants');
                    $skues[] = [
                        'product_id' => $product->id,
                        'name' => $variant['name'],
                        'price' => $variant['price'],
                        'sale_price' => $variant['sale_price'],
                        'barcode' => $product->id . $variant['barcode'],
                        'image' => str_replace('public/', '', $skuImages),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
                Skus::insert($skues);
            }
            DB::commit();
            return redirect()->route('admin.product.index')->with([
                'status_succeed' => 'Thêm sản phẩm thành công.'
            ]);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
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
        $skuses = Skus::whereNull('deleted_at')->where('product_id', $product->id)->paginate(10);
        return view('admin.products.show', compact(['brand', 'category', 'product', 'productImages', 'skuses']));
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

    public function update(ProductRequest $request, Product $product)
    {
        try {

            DB::beginTransaction();

            $product->name = $request->name;
            $product->description = $request->description;
            $product->id_category = $request->id_category;
            $product->id_brand = $request->id_brand;
            if (isset($request->image)) {
                if ($product->image) {
                    Storage::delete('public/' . $product->image);
                }
                $productpath = $request->image->store('public/products');
                $product->image = str_replace('public/', '', $productpath);
            }
            $product->save();

            if ($request->images) {
                $productImages = ProductImage::where('id_product', $product->id)->get();
                if ($productImages) {
                    foreach ($productImages as $productImage) {
                        Storage::delete('public/' . $productImage->image_url);
                    }
                    ProductImage::where('id_product', $product->id)->delete();
                }
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

                        $skusToUpdate[] = [
                            'barcode' => $variant['barcode'],
                            'name' => $variant['name'],
                            'price' => $variant['price'],
                            'sale_price' => $variant['sale_price'],
                            'updated_at' => Carbon::now(),
                        ];
                        if (isset($variant['image'])) {
                            $skuImagePath = $variant['image']->store('public/productsVariants');
                            Skus::where('barcode', $variant['barcode'])->update([
                                'image' => str_replace('public/', '', $skuImagePath)
                            ]);
                        }
                    } else {
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

            foreach ($skusToUpdate as $skuData) {
                Skus::where('barcode', $skuData['barcode'])->update([
                    'name' => $skuData['name'],
                    'price' => $skuData['price'],
                    'sale_price' => $skuData['sale_price'],
                    'updated_at' => Carbon::now(),
                ]);
            }

            if (!empty($skusToInsert)) {
                Skus::insert($skusToInsert);
            }
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
        DB::beginTransaction();
        Skus::where('product_id', $product->id)->delete();
        $product->delete();
        DB::commit();
        return redirect()->route('admin.product.index');
    }

    public function changeStatus(Product $product)
    {
        DB::beginTransaction();
        $product->status = $product->status ? 0 : 1;
        // Skus::whereNull('deleted_at')->where('product_id', $product->id)->update(['status' => $product->status]);
        if (!$product->save()) {
            DB::rollBack();
        }
        DB::commit();
        return redirect()->route('admin.product.index');
    }
}
