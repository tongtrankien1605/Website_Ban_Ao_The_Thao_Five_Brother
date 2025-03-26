<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus as EnumsOrderStatus;
use App\Events\OrderStatusUpdate;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use App\Models\PaymentMethodStatus;
use App\Models\Refund;
use Carbon\Carbon;
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
                $q->on('payment_method_statuses.id', '=', 'orders.id_payment_method_status');
            })
            ->select([
                'orders.*',
                'shipping_methods.name as shipping_method_name',
                'payment_methods.name as payment_method_name',
                'payment_method_statuses.name as payment_method_status_name',
                'order_statuses.name as order_status_name',
                'users.name as user_name',
            ])
            ->withSum('order_details', 'quantity')
            ->withCount('order_details')
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
            'payment_method_statuses',
            'vouchers',
            'order_details',
            'order_status_histories',
            'refunds'
        ])->find($id);
        if (!$order) {
            return redirect(route('admin.orders.index'))->with('error', 'đơn hàng không tồn tại');
        }
        $data = [EnumsOrderStatus::REFUND, EnumsOrderStatus::REFUND_FAILED, EnumsOrderStatus::SUCCESS];
        $orderStatuses = OrderStatus::whereNotIn('id', $data)->orderBy('id')->get();
        return view('admin.orders.show', compact('order', 'orderStatuses'));
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
        if ($order->id_order_status == EnumsOrderStatus::DELIVERED) {
            $order->delivered_at = Carbon::now();
        }
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
        return response()->json('Đơn hàng đã được cập nhật');
    }

    public function updateRefund(Request $request, $id)
    {
        DB::beginTransaction();
        $order = Order::findOrFail($id);
        if (!$order) {
            return redirect(route('admin.orders.index'))->with('error', 'đơn hàng không tồn tại');
        }
        $oldStatus = $order->id_order_status;
        $refund = Refund::where('id_order', $order->id)->first();

        if ($request->status == 'approved') {
            $order->id_order_status = EnumsOrderStatus::REFUND;
            $newStatus = $order->id_order_status;
            $note = "Đơn hàng đã được cập nhật thành 'Hoàn hàng'";
            $refund->status = 'Đã chấp nhận';
            $orderDetails = OrderDetail::where('id_order', $order->id)->get();
            foreach ($orderDetails as $orderDetail) {
                $inventory = Inventory::where('id_product_variant', $orderDetail->id_product_variant)->first();
                $oldQuantity = $inventory->quantity;
                $newQuantity = $inventory->quantity + $orderDetail->quantity;
                $inventory->quantity += $orderDetail->quantity;
                $inventory->save();
                InventoryLog::create([
                    'id_product_variant' => $orderDetail->id_product_variant,
                    'user_id' => auth()->user()->id,
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'change_quantity' => $orderDetail->quantity,
                    'reason' => "Hoàn hàng",
                ]);
            }
        } else {
            $order->id_order_status = EnumsOrderStatus::REFUND_FAILED;
            $newStatus = $order->id_order_status;
            $note = "Đơn hàng không được chấp nhận hoàn hàng";
            $refund->status = 'Đã từ chối';
        }
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'note' => $note,
        ]);

        if (!$refund->save() || !$order->save()) {
            DB::rollBack();
            return redirect(route('admin.orders.index'))->with('error', 'Đã xảy ra lỗi');
        }
        DB::commit();
        broadcast(new OrderStatusUpdate($order))->toOthers();
        return response()->json('Đơn hàng đã được cập nhật');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
