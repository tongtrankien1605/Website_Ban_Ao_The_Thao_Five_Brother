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
        try {
            DB::beginTransaction();

            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->id_category = $request->id_category;
            $product->id_brand = $request->id_brand;
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
                    $skues[] = [
                        'product_id' => $product->id,
                        'name' => $variant['name'],
                        'quantity' => $variant['quantity'],
                        'price' => $variant['price'],
                        'sale_price' => $variant['sale_price'],
                        'barcode' => $variant['barcode'],
                    ];
                }
                Skus::insert($skues);
            }
            DB::commit();
            return redirect()->route('admin.product.index')->with([
                'status_succeed' => 'Thêm sản phẩm thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // dd($product);
        $brand = Brand::whereNull('deleted_at')->where('id', $product->id_brand)->first();
        $category = Category::whereNull('deleted_at')->where('id', $product->id_category)->first();
        $productImages = ProductImage::whereNull('deleted_at')->where('id_product', $product->id)->get();
        // dd($productImages);
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
        return view('admin.products.edit', compact(['brands', 'categories', 'product', 'attributes', 'attributeValues']));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'description' => ['required', 'string'],
            'id_category' => ['required'], // Đảm bảo ID danh mục hợp lệ
            'id_brand'    => ['required'], // Đảm bảo ID thương hiệu hợp lệ
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'price'       => ['required', 'numeric']
        ], [
            'name.required'        => 'Tên sản phẩm không được để trống.',
            'name.unique'          => 'Tên sản phẩm đã tồn tại, vui lòng chọn tên khác.',
            'description.required' => 'Mô tả không được để trống.',
            'image.image'          => 'File phải là hình ảnh.',
            'image.mimes'          => 'Ảnh phải có định dạng: jpg, jpeg, png, gif.',
            'image.max'            => 'Kích thước ảnh tối đa là 2MB.',
        ]);
        // dd($data);
        try {
            // Nếu có file ảnh mới thì xóa ảnh cũ và lưu ảnh mới
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu có
                if ($product->image && Storage::exists('public/' . $product->image)) {
                    Storage::delete('public/' . $product->image);
                }

                // Lưu ảnh mới
                $imagePath = $request->file('image')->store('products', 'public');
                $data['image'] = $imagePath;
            } else {
                // Không có ảnh mới thì giữ nguyên ảnh cũ
                $data['image'] = $product->image;
            }

            // Cập nhật sản phẩm
            $product->update($data);

            return redirect()->route('admin.product.index')->with('message', 'Sửa sản phẩm thành công!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        $product->delete();
        return redirect()->route('admin.product.index');
    }
}
