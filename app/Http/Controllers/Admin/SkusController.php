<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SkusRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Skus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Product $product, $id)
    {
        $skus = Skus::where("id", $id)
            ->with(['variants.product_atribute_values'])
            ->first();
        return view('admin.skus.show', compact('skus', 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, string $id)
    {
        $skus = Skus::where("id", $id)->first();
        return view('admin.skus.edit', compact('skus', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SkusRequest $request, Product $product, string $id)
    {
        $skus = Skus::where("id", $id)->first();
        $skus->name = $request->name;
        $skus->price = $request->price;
        $skus->sale_price = $request->sale_price;
        if (isset($request->image)) {
            $skusImage = $request->image->store('public/productsVariants');
            $skus->image = str_replace('public/', '', $skusImage);
        }
        $skus->save();
        return redirect()->route('admin.product.skus.show', ['product' => $product, 'sku' => $id]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function changeStatus($product, $skus)
    {
        $skus = Skus::where('id', $skus)->first();
        if (!$skus) {
            return redirect()->back()->with('error', 'biến thể không tồn tại!');
        }
        if (CartItem::where('id_product_variant', $skus->id)->exists()) {
            return redirect()->back()->with('error', 'Không thể thay đổi trạng thái vì biến thể đang tồn tại trong giỏ hàng!');
        }
        $skus->status = $skus->status ? 0 : 1;
        if (!$skus->save()) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi');
        }
        return redirect()->route('admin.product.show', $product)->with('success', 'Trạng thái biến thể đã được cập nhật thành công!');
    }
}
