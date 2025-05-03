<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryEntry;
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
    public function index(Request $request)
    {
        $query = Product::query();

        // Sắp xếp
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest('id');
        }

        $products = $query->where('status',1)->paginate(8);
        
        // Lấy danh mục và thương hiệu cho bộ lọc
        $categories = Category::all();
        $brands = Brand::all();

        foreach ($products as $product) {
            $product->default_image = ProductImage::where('id_product', $product->id)
                ->where('is_default', 1)
                ->first()?->image_url ??
                ProductImage::where('id_product', $product->id)->first()?->image_url;
        }

        return view('client.shop', compact('products', 'categories', 'brands'));
    }
    public function indexMain()
    {
        $products = Product::where('status', 1)->latest('updated_at')->limit(8)->get();
        $posts = Post::latest('published_at')->limit(2)->get();

        $productFeatured = Product::where('status', 1)->latest('views')->limit(8)->get();

        return view('client.index', compact('products', 'posts', 'productFeatured'));
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
        if(!$product->status) {
            return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại');
        }
        $product->increment('views');
        $relatedProducts = Product::where('id_brand', $product->id_brand)
            ->where('id', '!=', $product->id)
            ->take(5)
            ->get();
        $popularProducts = Product::orderByDesc('views')
            ->where('id', '!=', $product->id)
            ->limit(5)
            ->get();
        $brand = Brand::find($product->id_brand);
        $category = Category::find($product->id_category);
        $brands = Brand::withCount('products')->get();
        $productImages = ProductImage::where('id_product', $product->id)->get();
        $wishlist = Wishlist::where('id_product', $product->id)->where('id_user', auth()->id())->first();

        $skus = Skus::where('product_id', $product->id)->with('inventory_entries')->get();
        // foreach ($skus as $sku) {
        //     $inventoryEntry = InventoryEntry::where('id_skus', $sku->id)->get();
        // }
        // dd($inventoryEntry);


        $variants = Variant::where('product_id', $product->id)
            ->with(['latestStock'])
            ->get();

        // Tạo variant map: "1,3" => SKU_ID
        $variantMap = [];

        $variants->groupBy('id_skus')->each(function ($group, $skuId) use (&$variantMap) {
            $valueIds = $group->pluck('product_attribute_value_id')->sort()->implode(',');
            $variantMap[$valueIds] = $skuId;
        });

        // Dữ liệu tồn kho theo SKU_ID
        $inventoryData = Inventory::whereIn('id_product_variant', array_values($variantMap))
            ->get();

        // dd($inventoryData);

        // Build inventory map: [sku_id] => quantity


        return view('client.single-product', compact(
            'brand',
            'category',
            'brands',
            'product',
            'productImages',
            'skus',
            'wishlist',
            'variants',
            'inventoryData',
            'variantMap',
            'relatedProducts', 
            'popularProducts',
            // 'inventoryEntry'
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
