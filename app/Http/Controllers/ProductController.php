<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductAtribute;
use App\Models\ProductImage;
use App\Models\Skus;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest('id')->paginate(8);
        foreach ($products as $product) {
            $product->default_image = ProductImage::where('id_product', $product->id)
                ->where('is_default', 1)
                ->first()?->image_url ??
                ProductImage::where('id_product', $product->id)->first()?->image_url;
        }

        return view('client.shop', compact('products'));
    }
    public function indexMain()
    {
        $products = Product::where('status', 1)->latest('updated_at')->limit(8)->get();
        $posts = Post::latest('published_at')->limit(2)->get();

        return view('client.index', compact('products', 'posts'));
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
    public function show($id)
    {
        $product = Product::with([
            'attributeValues.attribute', // Lấy thuộc tính và giá trị
            'variants'
        ])->findOrFail($id);
    // dd($product->toArray());
        $brand = Brand::find($product->id_brand);
        $category = Category::find($product->id_category);
        $productImages = ProductImage::where('id_product', $product->id)->get();
        $skus = Skus::where('product_id', $product->id)->get();
    
        $mainImage = $product->image ?? ($productImages->first() ? $productImages->first()->image_url : null);
    
        // Debug để kiểm tra dữ liệu trước khi truyền vào view
        // dd($product->attributeValues);
    
        return view('client.single-product', compact([
            'brand',
            'category',
            'product',
            'productImages',
            'mainImage',
            'skus',
        ]));
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
