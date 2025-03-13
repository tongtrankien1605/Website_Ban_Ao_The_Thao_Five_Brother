<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderStatusUpdate;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use App\Models\PaymentMethodStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest('id')
            ->join('users', function ($q) {
                $q->on('users.id', '=', 'orders.id_user');
                $q->whereNull('users.deleted_at');
            })
            ->join('order_statuses', function ($q) {
                $q->on('order_statuses.id', '=', 'orders.id_order_status');
            })
            ->join('shipping_methods', function ($q) {
                $q->on('shipping_methods.id_shipping_method', '=', 'orders.id_shipping_method');
            })
            ->join('payment_methods', function ($q) {
                $q->on('payment_methods.id_payment_method', '=', 'orders.id_payment_method');
            })
            ->join('payment_method_statuses', function ($q) {
                $q->on('payment_methods.id_payment_method_status', '=', 'payment_method_statuses.id');
            })
            ->select([
                'orders.*',
                'shipping_methods.name as shipping_method_name',
                'payment_methods.name as payment_method_name',
                'payment_method_statuses.name as payment_method_status_name',
                'order_statuses.name as order_status_name',
                'users.name as user_name'
            ])
            ->paginate(10);
        // dd($orders);
        $orderStatuses = OrderStatus::orderBy('id')->get();
        $paymentMethodStatuses = PaymentMethodStatus::orderBy('id')->get();
        return view('admin.orders.index', compact(['orders', 'orderStatuses', 'paymentMethodStatuses']));
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
    public function show(string $id)
    {
        $order = Order::with([
            'address_users',
            'order_statuses',
            'shipping_methods',
            'payment_methods',
            'vouchers',
            'order_details',
            'order_status_histories',
        ])->find($id);
        // dd($order->address_users->toArray());
        if (!$order) {
            return redirect(route('admin.orders.index'))->with('error', 'đơn hàng không tồn tại');
        }
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        $order = Order::where('id', $id)->first();
        $oldStatus = $order->id_order_status;
        if (!$order) {
            return redirect(route('admin.orders.index'))->with('error', 'đơn hàng không tồn tại');
        }
        $order->id_order_status = $request->id_order_status;
        if (!$order->save()) {
            DB::rollBack();
            return redirect(route('admin.orders.index'))->with('error', 'Đã xảy ra lỗi');
        }

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => $request->id_order_status,
            'note' => $request->note,
        ]);
        DB::commit();

        broadcast(new OrderStatusUpdate($order))->toOthers();
        return response()->json('hay lắm cu');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
