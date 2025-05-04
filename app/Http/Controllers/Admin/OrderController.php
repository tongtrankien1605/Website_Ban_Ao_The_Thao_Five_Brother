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
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */




    public function index()
    {
        $orders = Order::latest('id')->where('orders.id_payment_method_status', '!=', 4)
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
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        // dd($orders);
        $allOrderStatus = OrderStatus::orderBy('id')->get();
        $data = [EnumsOrderStatus::REFUND, EnumsOrderStatus::REFUND_FAILED, EnumsOrderStatus::SUCCESS, EnumsOrderStatus::WAIT_CONFIRM, EnumsOrderStatus::REFUND_SUCCESS, EnumsOrderStatus::RETURN, EnumsOrderStatus::AUTHEN, EnumsOrderStatus::CANCEL, EnumsOrderStatus::WAIT_REFUND];
        $orderStatuses = OrderStatus::whereNotIn('id', $data)->orderBy('id')->get();
        $paymentMethodStatuses = PaymentMethodStatus::orderBy('id')->get();
        return view('admin.orders.index', compact(['orders', 'orderStatuses', 'paymentMethodStatuses', 'allOrderStatus']));
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
        $data = [EnumsOrderStatus::REFUND, EnumsOrderStatus::REFUND_FAILED, EnumsOrderStatus::SUCCESS, EnumsOrderStatus::WAIT_CONFIRM, EnumsOrderStatus::REFUND_SUCCESS, EnumsOrderStatus::RETURN, EnumsOrderStatus::AUTHEN, EnumsOrderStatus::CANCEL, EnumsOrderStatus::WAIT_REFUND];
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

        if ($request->status == 'approved') {
            $order->id_order_status = EnumsOrderStatus::DELIVERED;
            $order->delivered_at = Carbon::now();
            $note = "Đơn hàng đã được giao đến bạn";
        }
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'old_status' => $oldStatus,
            'new_status' => $order->id_order_status,
            'note' => $note,
        ]);

        if (!$order->save()) {
            DB::rollBack();
            return redirect(route('admin.orders.index'))->with('error', 'Đã xảy ra lỗi');
        }
        DB::commit();
        broadcast(new OrderStatusUpdate($order))->toOthers();
        return response()->json('Đơn hàng đã được cập nhật');
    }

    public function downloadPdf($id)
    {
        $order = Order::with(['users', 'address_users', 'payment_methods', 'shipping_methods', 'order_statuses', 'order_details.product_variants'])
            ->findOrFail($id);

        $pdf = PDF::loadView('admin.orders.pdf', compact('order'));

        return $pdf->download('don-hang-' . $id . '.pdf');
    }

    public function downloadMultiplePdf(Request $request)
    {
        $orderIds = $request->input('order_ids', []);

        if (empty($orderIds)) {
            return back()->with('error', 'Vui lòng chọn ít nhất một đơn hàng');
        }

        $orders = Order::with(['users', 'address_users', 'payment_methods', 'shipping_methods', 'order_statuses', 'order_details.product_variants'])
            ->whereIn('id', $orderIds)
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'Không tìm thấy đơn hàng');
        }

        // Tạo một file PDF duy nhất chứa tất cả các đơn hàng
        $pdf = PDF::loadView('admin.orders.multiple_pdf', compact('orders'));

        return $pdf->download('danh-sach-don-hang-' . time() . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateMultipleStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $orderIds = $request->input('order_ids', []);
            $newStatus = $request->input('new_status');
            $note = $request->input('note');

            foreach ($orderIds as $orderId) {
                $order = Order::findOrFail($orderId);
                $oldStatus = $order->id_order_status;

                // Cập nhật trạng thái đơn hàng
                $order->id_order_status = $newStatus;
                if ($newStatus == EnumsOrderStatus::DELIVERED) {
                    $order->delivered_at = Carbon::now();
                }

                if (!$order->save()) {
                    throw new \Exception('Không thể cập nhật đơn hàng #' . $orderId);
                }

                // Tạo lịch sử thay đổi trạng thái
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'note' => $note,
                ]);

                // Broadcast sự kiện cập nhật trạng thái
                broadcast(new OrderStatusUpdate($order))->toOthers();
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái đơn hàng thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
