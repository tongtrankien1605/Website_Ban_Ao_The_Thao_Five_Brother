<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    private Brand $brand;
    
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::latest('updated_at')->paginate(20);
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $newBrand = new Brand();
            $newBrand->name = $request->name;
            $newBrand->save();

            return redirect()->route('admin.brand.index')->with('success', 'Thêm thương hiệu thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::find($id);
        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::find($id);
        
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        try {
            $brand->name = $request->name;
            $brand->save();

            return redirect()->route('admin.brand.index')->with('success', 'Cập nhật thương hiệu thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return back()->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function forceDestroy(Brand $brand)
    {
        try {
            $brand->forceDelete();
            return back()->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
