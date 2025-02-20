<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAtribute;
use App\Models\ProductAtributeValue;
use Illuminate\Http\Request;
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
        $products = Product::select(['products.*', 'categories.name as product_category', 'brands.name as product_brand'])
            ->join('categories', function ($q) {
                $q->on('categories.id', '=', 'products.id_category');
                $q->whereNull('categories.deleted_at');
            })
            ->join('brands', function ($q) {
                $q->on('brands.id', '=', 'products.id_brand');
                $q->whereNull('brands.deleted_at');
            })->orderByDesc('products.updated_at')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attribute = ProductAtribute::get()->pluck('name', 'id');
        $attributeValues = ProductAtributeValue::whereIn('product_attribute_id', $attribute->keys())
        ->get()
        ->groupBy('product_attribute_id');
        $brands = Brand::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        // dd($attibute, $value, $brands, $categories);
        return view('admin.products.create', compact(['brands', 'categories', 'attribute', 'attributeValues']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('products', 'name')],
            'description' => ['required', 'string'],
            'id_category' => ['required', 'exists:categories,id'], // Check id tồn tại
            'id_brand'    => ['required', 'exists:brands,id'], // Check id tồn tại
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'price'       => ['required', 'numeric']
        ], [
            'name.required'        => 'Tên sản phẩm không được để trống.',
            'name.unique'          => 'Tên sản phẩm đã tồn tại, vui lòng chọn tên khác.',
            'description.required' => 'Mô tả không được để trống.',
            'image.image'          => 'File phải là hình ảnh.',
            'image.mimes'          => 'Ảnh phải có định dạng: jpg, jpeg, png, gif.',
            'image.max'            => 'Kích thước ảnh tối đa là 2MB.',
            'id_category.exists'   => 'Danh mục không hợp lệ.',
            'id_brand.exists'      => 'Thương hiệu không hợp lệ.',
        ]);

        try {
            // Kiểm tra nếu có file ảnh thì lưu vào storage
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            } else {
                $data['image'] = null; // 🔥 Đảm bảo luôn có giá trị
            }

            // Kiểm tra dữ liệu trước khi lưu
            if (empty($data['image']) && $request->hasFile('image')) {
                throw new \Exception('Lưu ảnh thất bại, vui lòng thử lại.');
            }

            // Tạo sản phẩm mới
            Product::create($data);

            return redirect()->route('admin.product.index')->with('message', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $brands = Brand::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        // dd($product->image);
        return view('admin.products.show', compact(['brands', 'categories', 'product']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $attibute = ProductAtribute::get();
        $value = ProductAtributeValue::get();
        $brands = Brand::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        // dd($product->image);
        return view('admin.products.edit', compact(['brands', 'categories', 'product', 'attibute', 'value']));
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
