<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\RefundHistory;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Events\OrderStatusUpdate;
use App\Models\Inventory;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $refunds = Refund::with('user', 'order.order_statuses', 'refund_history')->latest()->paginate(10);
        return view('admin.refunds.index', compact('refunds'));
    }

    public function show($id)
    {
        $refund = Refund::findOrFail($id);
        return view('admin.refunds.show', compact('refund'));
    }

    /**
     * Xử lý yêu cầu hoàn tiền
     */
    public function process(Request $request, $id)
    {
        $request->validate([
            'refund_id' => 'required|exists:refunds,id',
            'user_id' => 'required|exists:users,id',
            'note' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            'action' => 'required|in:accept,reject',
        ]);

        try {
            DB::beginTransaction();

            // Tìm refund
            $refund = Refund::findOrFail($id);
            $backStatus = $refund->old_status;

            // Tìm đơn hàng liên quan
            $order = Order::with('order_details')->findOrFail($refund->id_order);

            // Kiểm tra trạng thái hiện tại của yêu cầu
            if ($refund->status !== 'Đang chờ xử lý') {
                return redirect()->back()->with('error', 'Yêu cầu này đã được xử lý trước đó');
            }

            // Xử lý lưu ảnh minh chứng


            // Tạo lịch sử hoàn tiền
            $refundHistory = new RefundHistory([
                'refund_id' => $refund->id,
                'user_id' => $request->user_id,
                'note' => $request->note,
                'image' => $request->file('image')->store('refund_histories', 'public'),
            ]);
            $refundHistory->save();

            // Cập nhật trạng thái yêu cầu hoàn tiền
            if ($request->action === 'accept') {
                $refund->status = 'Đã chấp nhận';
                // Cập nhật trạng thái đơn hàng thành Đã hủy/Hoàn tiền
                if ($order->id_order_status = OrderStatus::DELIVERED) {
                    $order->id_order_status = OrderStatus::REFUND_SUCCESS;
                } else {
                    $order->id_order_status = OrderStatus::CANCEL;
                }
                foreach ($order->order_details as $item) {
                    $inventory = Inventory::where('id_product_variant', $item->id_product_variant)->first();
                    $oldQuantity = $inventory->quantity;
                    $inventory->quantity += $item->quantity;
                    $inventory->save();
                    InventoryLog::create([
                        'id_product_variant' => $item->id_product_variant,
                        'user_id' => $refund->user_id,
                        'old_quantity' => $oldQuantity,
                        'new_quantity' => $inventory->quantity,
                        'change_quantity' => $item->quantity,
                        'reason' => "Khách hàng hủy đơn",
                    ]);
                }
                $order->id_payment_method_status = 3;
            } else {
                $refund->status = 'Đã từ chối';
                // Nếu từ chối, đưa đơn hàng về trạng thái Đã xác nhận
                if ($order->id_order_status = OrderStatus::DELIVERED) {
                    $order->id_order_status = OrderStatus::REFUND_FAILED;
                } else {
                    $order->id_order_status = $backStatus;
                }
            }

            $refund->save();
            $order->save();
            broadcast(new OrderStatusUpdate($order))->toOthers();
            DB::commit();

            return redirect()->route('admin.refunds.index')
                ->with('success', 'Yêu cầu đã được xử lý');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
