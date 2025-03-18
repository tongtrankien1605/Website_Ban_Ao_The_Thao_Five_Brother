<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAtributeRequest;
use App\Models\ProductAtribute;
use App\Models\ProductAtributeValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = ProductAtribute::latest('id')->paginate(10);
        $attributeIds = $attributes->pluck('id'); // Lấy danh sách ID từ collection

        $attributeValues = ProductAtributeValue::whereIn('product_attribute_id', $attributeIds)
            ->get()
            ->groupBy('product_attribute_id');
        // dd($attributeValues);
        return view('admin.productAttribute.index', compact(['attributes', 'attributeValues']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.productAttribute.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductAtributeRequest $request)
    {
        try {
            DB::beginTransaction();
            $attribute = new ProductAtribute();
            $attribute->name = $request->name;
            $attribute->save();

            $values = [];
            foreach ($request->values as $value) {
                $values[] = [
                    'product_attribute_id' => $attribute->id,
                    'value' => $value,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            ProductAtributeValue::insert($values);
            DB::commit();
            return redirect()->route('admin.product_attribute.index')->with([
                'status_succeed' => 'Thành công'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $productAtribute = ProductAtribute::where('id', $id)->first();
        $values = ProductAtributeValue::where('product_attribute_id', $productAtribute->id)->get();
        return view('admin.productAttribute.edit', compact(['productAtribute', 'values']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductAtributeRequest $request, string $id)
    {
        DB::beginTransaction();

        try {
            $productAtribute = ProductAtribute::findOrFail($id);
            $productAtribute->name = $request->name;
            $productAtribute->save();
            $oldValues = ProductAtributeValue::where('product_attribute_id', $id)->get()->keyBy('id');

            $existingIds = [];
            foreach ($request->values as $value) {
                if (isset($value['id']) && $value['id'] !== "new_") {
                    $attributeValue = $oldValues[$value['id']] ?? null;
                    if ($attributeValue && $attributeValue->value !== $value['value']) {
                        $attributeValue->update(['value' => $value['value']]);
                    }
                    $existingIds[] = $value['id'];
                }
            }

            foreach ($request->values as $value) {
                if (!isset($value['id']) || $value['id'] === "new_") {
                    $newValue = ProductAtributeValue::create([
                        'product_attribute_id' => $id,
                        'value' => $value['value']
                    ]);
                    $existingIds[] = $newValue->id;
                }
            }

            ProductAtributeValue::where('product_attribute_id', $id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            DB::commit();

            return redirect()->route('admin.product_attribute.index')->with([
                'status_succeed' => 'Thuộc tính đã được cập nhật thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật!']);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        ProductAtributeValue::where('product_attribute_id', $id)->forceDelete();
        ProductAtribute::where('id', $id)->forceDelete();
        DB::commit();
        return redirect()->route('admin.product_attribute.index');
    }
}
