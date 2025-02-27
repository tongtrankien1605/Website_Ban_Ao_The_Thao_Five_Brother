<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\Skus;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Skus::where('status',1)->latest('updated_at')->paginate(8);
        // dd($data);
        return view('client.shop', compact('data'));
    }
    public function indexMain()
    {
        $products = Product::where('status', 1)->latest('updated_at')->limit(8)->get();
        // dd($products->toArray());
        $posts = Post::latest('published_at')->limit(2)->get();
        //  dd($data);
        return view('client.index', compact(['products', 'posts']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        // dd($product->id);
        return view('client.single-product', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
