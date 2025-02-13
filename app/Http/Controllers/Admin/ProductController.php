<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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
        $brands = Brand::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        return view('admin.products.create', compact(['brands','categories']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $brands = Brand::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        dd($product->image);
        return view('admin.products.edit', compact(['brands','categories','product']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
