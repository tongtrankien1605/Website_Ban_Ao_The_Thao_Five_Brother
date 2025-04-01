<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductAtribute;
use App\Models\ProductImage;
use App\Models\Skus;
use App\Models\Wishlist;
use App\Models\Variant;
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
            'attributeValues.attribute',
            'variants.latestStock'
        ])->findOrFail($id);

        $brand = Brand::find($product->id_brand);
        $category = Category::find($product->id_category);
        $productImages = ProductImage::where('id_product', $product->id)->get();
        $wishlist = Wishlist::where('id_product', $product->id)->where('id_user', auth()->id())->first();
        
        // Get all SKUs for this product
        $skus = Skus::where('product_id', $product->id)->get();
        
        // Get all variants for this product
        $variants = Variant::where('product_id', $product->id)
            ->with(['latestStock', 'product_atribute_values'])
            ->get();

        // Get inventory data for all variants
        $inventoryData = Inventory::whereIn('id_product_variant', $variants->pluck('id'))
            ->latest('created_at')
            ->get()
            ->groupBy('id_product_variant')
            ->map(function($group) {
                return $group->first()->quantity;
            })
            ->toArray();

        // Get the main image
        $mainImage = $product->image ?? ($productImages->first() ? $productImages->first()->image_url : null);

        return view('client.single-product', compact(
            'brand',
            'category',
            'product',
            'productImages',
            'mainImage',
            'skus',
            'wishlist',
            'variants',
            'inventoryData'
        ));
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
