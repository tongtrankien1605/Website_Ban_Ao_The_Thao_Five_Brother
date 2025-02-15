<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'total_usage' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => ['boolean,nullable',Rule::in([0,1])],
        ]);

        Voucher::create($request->all());

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        $data=$request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'total_usage' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => ['nullable',Rule::in([0,1])],
        ]);

        $data['is_active'] ??= 0;

        dd($data);
        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher updated successfully');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher deleted successfully');
    }
}
