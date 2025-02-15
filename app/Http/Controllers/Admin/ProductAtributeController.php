<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAtributeRequest;
use App\Models\Product;
use App\Models\ProductAtribute;
use App\Models\ProductAtributeValue;
use App\Models\Skus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductAtributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($product)
    {
        $product = Product::where('id', $product)->first();
        $skuses = Skus::where('product_id', $product->id)->orderByDesc('updated_at')->paginate(10);
        return view('admin.productAttribute.index', compact(['product', 'skuses']));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($product)
    {
        $item = Product::where('id', $product)->first();
        return view('admin.productAttribute.create', compact(['product', 'item']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductAtributeRequest $request, $product)
    {

        // dd($request->input('attributes'));

        foreach ($request->input('attributes') as $attr) {
            $attribute = new ProductAtribute();
            $attribute->name = $attr['name'];
            $attribute->save();

            $attributeValues = [];
            foreach ($attr['values'] as $value) {
                $attributeValues[] = [
                    'product_attribute_id' => $attribute->id,
                    'product_id' => $product,
                    'value' => $value,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            ProductAtributeValue::insert($attributeValues);
        }
        $skuses = [];
        foreach ($request->variants as $variant) {
            $skuses[] = [
                'product_id' => $product,
                'name' => $variant['attributes'],
                'price' => $variant['price'],
                'quantity' => $variant['quantity'],
                'barcode' => $variant['barcode'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        Skus::insert($skuses);

        return redirect()->route('admin.product.product_attribute.index', $product)->with('success', 'Thuộc tính đã được thêm thành công!');
    }



    /**
     * Display the specified resource.
     */
    public function show($product, $skus)
    {

        $skus = Skus::where('id', $skus)->first();
        return view('admin.productAttribute.show', compact(['product', 'skus']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product,$skus)
    {
        //
        $skus = Skus::where('id', $skus)->first();
        // dd($product);
        return view('admin.productAttribute.edit', compact(['product', 'skus']));
    }

    /**
     * Update the specified resource in storage.
     */

     public function update($product, $product_attribute, Request $request)
     {
         // Lấy SKU từ database
         $skus = Skus::findOrFail($product_attribute);
     
         $data = $request->validate([
             'name' => 'required|string|max:255',
             'quantity' => 'required|integer|min:1',
             'price' => 'required|numeric|min:0',
             'barcode' => 'nullable|string|max:255',
             'product_id' => 'required|exists:products,id',
         ]);
     
         $skus->update($data);
     
         return redirect()->route('admin.product.index')->with('success', 'Sản phẩm đã được cập nhật');
     }
     


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product, $skus)
    {
        $skus = Skus::where('id', $skus)->first();
        $skus->delete();
        return redirect()->route('admin.product.product_attribute.index', $product);
    }
}
