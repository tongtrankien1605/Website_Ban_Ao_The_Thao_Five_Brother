<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Enums\OrderStatus;
use App\Events\OrderStatusUpdate;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CancelledOrderController extends Controller
{
    public function index()
    {
        $cancelledOrders = OrderHistory::with(['orders.users', 'orders.order_statuses', 'orders.order_histories'])
            // ->whereIn('id_order_status', [OrderStatus::CANCEL])
            ->latest()
            ->paginate(10);
        // dd($cancelledOrders->toArray());
        return view('admin.cancelled-orders.index', compact('cancelledOrders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'order_statuses', 'order_history', 'order_details.product_variant.product'])
            ->findOrFail($id);
        return view('admin.cancelled-orders.show', compact('order'));
    }

    public function process(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'note' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            'action' => 'required|in:accept,reject',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::with('order_details')->findOrFail($request->order_id);
            // dd($order->toArray());
            $oldStatus = $order->id_order_status;

            if ($order->id_order_status !== OrderStatus::WAIT_REFUND) {
                return redirect()->back()->with('error', 'Đơn hàng này đã được xử lý!');
            }

            // Tạo lịch sử đơn hàng
            OrderHistory::where('id',$id)->update([
                // 'order_id' => $order->id,
                'user_id' => $request->user_id,
                'note_admin' => $request->note,
                'image' => $request->file('image')->store('order_histories', 'public'),
                'status' => 'Đã xử lý',
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => OrderStatus::CANCEL,
                'note' => 'Giao lại',
            ]);

            // $orderHistory->save();

            if ($request->action === 'accept') {
                $order->id_order_status = OrderStatus::CANCEL;
                
                // Cập nhật số lượng tồn kho
                foreach ($order->order_details as $item) {
                    $inventory = Inventory::where('id_product_variant', $item->id_product_variant)->first();
                    $oldQuantity = $inventory->quantity;
                    $inventory->quantity += $item->quantity;
                    $inventory->save();
                    
                    InventoryLog::create([
                        'id_product_variant' => $item->id_product_variant,
                        'user_id' => $order->user_id,
                        'old_quantity' => $oldQuantity,
                        'new_quantity' => $inventory->quantity,
                        'change_quantity' => $item->quantity,
                        'reason' => "Khách hàng hủy đơn",
                    ]);
                }
                $order->id_payment_method_status = 3; // Đã hoàn tiền
            } else {
                $order->id_order_status = OrderStatus::REFUND_FAILED;
            }

            $order->save();
            broadcast(new OrderStatusUpdate($order))->toOthers();
            
            DB::commit();

            return redirect()->route('admin.cancelled-orders.index')
                ->with('success', 'Đã xử lý đơn hàng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
} 