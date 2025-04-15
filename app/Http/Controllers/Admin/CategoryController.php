<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()->latest('id')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('categories', 'name')],
            'description' => ['required', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'is_active'   => ['nullable', Rule::in([0, 1])],
        ], [
            'name.required'        => 'Tên danh mục không được để trống.',
            'name.unique' => 'Tên danh mục đã tồn tại, vui lòng chọn tên khác.',
            'description.required' => 'Mô tả không được để trống.',
            'image.image'          => 'File phải là hình ảnh.',
            'image.mimes'          => 'Ảnh phải có định dạng: jpg, jpeg, png, gif.',
            'image.max'            => 'Kích thước ảnh tối đa là 2MB.',
            'is_active.boolean'    => 'Trạng thái phải là true hoặc false.',
        ]);

        try {
            // Kiểm tra và lưu ảnh nếu có
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('categories', 'public');
            }

            // Tạo danh mục
            // dd($data);
            Category::create($data);

            return redirect()->route('admin.category.index')->with('success', 'Thêm danh mục thành công!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        if (empty($keyword)) {
            return redirect()->route('admin.category.index')->with('error', 'Vui lòng nhập từ khóa tìm kiếm!');
        }

        $categories = Category::where('name', 'like', "%$keyword%")
            ->orWhere('description', 'like', "%$keyword%")
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::find($id); // Lấy dữ liệu từ DB

        if (!$category) {
            return redirect()->route('admin.category.index')->with('error', 'Category not found!');
        }

        return view('admin.categories.edit', compact('category'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Định dạng ảnh hợp lệ
            'is_active' => ['nullable', Rule::in([0, 1])],
        ]);

        // Xử lý ảnh nếu có file mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($category->image) {
                Storage::delete('public/' . $category->image);
            }

            // Lưu ảnh mới vào storage
            $imagePath = $request->file('image')->store('categories', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Cập nhật dữ liệu
        $validatedData['is_active'] ??= 0;
        // dd($validatedData);
        $category->update($validatedData);


        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.category.index')->with('success', 'Xóa thành công');
    }
}
