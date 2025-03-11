<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\CartItem;
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
            $product->image = $request->file('image')->store('products', 'public');
            $product->save();

            ProductImage::create([
                'id_product' => $product->id,
                'image_url' => $product->image,
                'is_default' => 1,
            ]);

            if ($request->images) {
                $imageNews = [];
                foreach ($request->images as $image) {
                    $imageNews[$image->getClientOriginalName()] = $image;
                }
                $imageMain = [$request->image->getClientOriginalName()];
                $imageAdds = array_diff(array_keys($imageNews), $imageMain);
                $productImages = [];
                foreach ($imageAdds as $imageName) {
                    $imageAdd = $imageNews[$imageName];
                    $productImages[] = [
                        'id_product' => $product->id,
                        'image_url' => $imageAdd->store('products', 'public'),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
                ProductImage::insert($productImages);
            }
            if (!empty($request->variants)) {
                $skuses = [];
                foreach ($request->variants as $variant) {
                    $sku = Skus::create([
                        'product_id' => $product->id,
                        'name' => $variant['name'],
                        'price' => $variant['price'],
                        'sale_price' => $variant['sale_price'],
                        'barcode' => $product->id . $variant['barcode'],
                        'image' => $variant['image']->store('productsVariants', 'public'),
                    ]);
                    $skuses[] = [
                        'id' => $sku->id,
                        'attribute_values' => $variant['attribute_values'],
                    ];
                }
                $variantsData = [];
                foreach ($skuses as $skuData) {
                    foreach ($skuData['attribute_values'] as $attributeValueId) {
                        $variantsData[] = [
                            'product_id' => $product->id,
                            'id_skus' => $skuData['id'],
                            'product_attribute_value_id' => $attributeValueId,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }
                }
                Variant::insert($variantsData);
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
        // $data = Variant::select('id_skus')
        // ->where('product_id', $product->id)
        // ->whereIn('product_attribute_value_id', [7, 5, 2])
        // ->groupBy('id_skus')
        // ->havingRaw('COUNT(DISTINCT product_attribute_value_id) = ?', [3])
        // ->first();
        $variants = Variant::where('product_id', $product->id)->get()->groupBy('product_attribute_value_id')->keys()->flatten()->toArray();
        $attributes = ProductAtribute::get()->pluck('name', 'id');
        $attributeValues = ProductAtributeValue::whereIn('product_attribute_id', $attributes->keys())
            ->get()
            ->groupBy('product_attribute_id');
        $brands = Brand::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        $productImages = ProductImage::whereNull('deleted_at')->where('id_product', $product->id)->get();
        $skues = Skus::whereNull('deleted_at')->where('product_id', $product->id)->get();
        $skuesArray = Skus::whereNull('deleted_at')->where('product_id', $product->id)->pluck('name', 'id');
        $skusAttributeValues =  Variant::leftJoin('product_atribute_values', function ($q) {
            $q->on('product_atribute_values.id', '=', 'variants.product_attribute_value_id');
            $q->whereNull('product_atribute_values.deleted_at');
        })->whereIn('id_skus', $skuesArray->keys())->get()->groupBy('id_skus');
        return view('admin.products.edit', compact(['brands', 'categories', 'product', 'attributes', 'attributeValues', 'productImages', 'skues', 'variants', 'skusAttributeValues']));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(ProductRequest $request, Product $product)
    {
        // dd($request->toArray());
        try {

            DB::beginTransaction();

            $product->name = $request->name;
            $product->description = $request->description;
            $product->id_category = $request->id_category;
            $product->id_brand = $request->id_brand;
            if (isset($request->image)) {
                if ($product->image) {
                    ProductImage::where('id_product', $product->id)->where('is_default', 1)->delete();
                    Storage::disk('public')->delete($product->image);
                }
                $product->image = $request->image->store('products', 'public');
                ProductImage::create([
                    'id_product' => $product->id,
                    'image_url' => $product->image,
                    'is_default' => 1,
                ]);
            }
            $product->save();

            if ($request->images) {
                $productImages = ProductImage::where('id_product', $product->id)->where('is_default', 0)->get();
                if ($productImages) {
                    foreach ($productImages as $productImage) {
                        Storage::disk('public')->delete($productImage->image_url);
                    }
                    ProductImage::where('id_product', $product->id)->delete();
                }
                
                $imageUpdates = [];
                foreach ($request->images as $image) {
                    $imageUpdates[$image->getClientOriginalName()] = $image;
                }
                $imageMain = isset($request->image) ? [$request->image->getClientOriginalName()] : [];
                $imageAdds = array_diff(array_keys($imageUpdates), $imageMain);
                $productImages = [];
                foreach ($imageAdds as $image) {
                    $imageAdd = $imageUpdates[$image];
                    $productImages[] = [
                        'id_product' => $product->id,
                        'image_url' => $imageAdd->store('products', 'public'),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
                ProductImage::insert($productImages);
            }

            if ($request->variants) {
                $requestIds = array_keys($request->variants);
                Skus::where('product_id', $product->id)->whereNotIn('id', $requestIds)->update(['status' => 0]);
                $skusIds = Skus::where('product_id', $product->id)->pluck('id')->toArray();
                $skusToUpdate = [];
                $variantsData = [];
                // dd($request->variants);
                foreach ($request->variants as $key => $variant) {
                    if (in_array($key, $skusIds)) {
                        $skusToUpdate[] = [
                            'id' => $key,
                            'barcode' => $variant['barcode'],
                            'name' => $variant['name'],
                            'price' => $variant['price'],
                            'sale_price' => $variant['sale_price'],
                            'updated_at' => Carbon::now(),
                        ];
                        if (isset($variant['status'])) {
                            Skus::where('id', $key)->update([
                                'status' => $variant['status']
                            ]);
                        } else {
                            Skus::where('id', $key)->update([
                                'status' => 0
                            ]);
                        }
                        if (isset($variant['image']) && $variant['image']->isValid()) {
                            Skus::where('id', $key)->update([
                                'image' => $variant['image']->store('productsVariants', 'public')
                            ]);
                        }
                    } else {
                        $sku = Skus::create([
                            'product_id' => $product->id,
                            'name' => $variant['name'],
                            'price' => $variant['price'],
                            'sale_price' => $variant['sale_price'],
                            'barcode' => $variant['barcode'],
                            'image' => $variant['image']->store('productsVariants', 'public'),
                        ]);
                        foreach ($variant['attribute_values'] as $attributeValueId) {
                            $variantsData[] = [
                                'product_id' => $product->id,
                                'id_skus' => $sku->id,
                                'product_attribute_value_id' => $attributeValueId,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    }
                }

                if (!empty($variantsData)) {
                    Variant::insert($variantsData);
                }
                foreach ($skusToUpdate as $skuData) {
                    Skus::where('id', $skuData['id'])
                        ->update([
                            'name' => $skuData['name'],
                            'price' => $skuData['price'],
                            'barcode' => $skuData['barcode'],
                            'sale_price' => $skuData['sale_price'],
                        ]);
                }
            } else {
                Skus::where('product_id', $product->id)->update(['status' => 0]);
            }



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
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại!');
        }
        $skus = Skus::where('product_id', $product->id)->pluck('id')->toArray();
        $checkCartExists = CartItem::whereIn('id_product_variant', $skus)->exists();
        if ($checkCartExists) {
            return redirect()->back()->with('error', 'Không thể thay đổi trạng thái vì biến thể của sản phẩm đang tồn tại trong giỏ hàng!');
        }

        $product->status = $product->status ? 0 : 1;
        if (!$product->save()) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi');
        }
        return redirect()->route('admin.product.index')->with('success', 'Trạng thái sản phẩm đã được cập nhật thành công!');
    }
}
