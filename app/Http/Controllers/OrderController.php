<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\OrderStatusHistory;
use App\Models\Refund;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $cartItem = CartItem::whereIn('id', $request->cart_item_ids)->with('skuses')->get();
        $total = 0;
        foreach ($cartItem as $item) {
            $total += $item->price * $item->quantity;
        }
        $request->validate([
            'shipping_id' => 'required|exists:shipping_methods,id_shipping_method',
            'payment_method' => 'required|exists:payment_methods,id_payment_method',
            'shipping_cost' => 'required|numeric',
            'grand_total' => 'required|numeric',
        ]);
        // dd($request->all(),$total);

        // dd($cartItem->toArray());
        $order = Order::create([
            'id_user' => Auth::id(),
            'id_address' => $request->address_id,
            'phone_number' => $request->phone_number,
            'id_shipping_method' => $request->shipping_id,
            'id_payment_method' => $request->payment_method,
            'total_amount' => $request->grand_total,
            'id_order_status' => 1, // Đơn hàng mới
            'id_payment_method_status' => 1, // "Chưa thanh toán"
        ]);


        foreach ($cartItem as $item) {
            OrderDetail::create([
                'id_order' => $order->id,
                'id_product_variant' => $item->id_product_variant,
                'quantity' => $item->quantity,
                'unit_price' => $item->skuses->sale_price,
                'total_price' => $item->quantity * $item->skuses->sale_price,
            ]);
        }

        // $cart->delete();

        if ($request->payment_method == 1) {
            CartItem::whereIn('id', $request->cart_item_ids)->delete();
            // $order->update(['id_order_status' => 2]); // "Đã thanh toán"
            return redirect()->route('order_success')->with('success', 'Đơn hàng của bạn sẽ được giao COD!');
        }

        $paymentController = new PaymentController();
        return $paymentController->processPayment($request, $order);
    }

    public function update(Request $request, $orderId)
    {
        $order = Order::with('order_details')->findOrFail($orderId);

        if ($order->id_user !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền yêu cầu hoàn hàng cho đơn hàng này.');
        }

        $oldStatus = $order->id_order_status;
        $newStatus = $request->id_order_status;


        if ($newStatus == OrderStatus::SUCCESS) {
            $order->id_payment_method_status = 2;
            $order->id_order_status = $newStatus;
            $order->save();
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => 'Khách hàng đã nhận hàng',
            ]);
        } else {
            $order->id_order_status = $newStatus;
            $order->save();
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => 'Yêu cầu hoàn hàng: ' . $request->reason,
            ]);
            Refund::create([
                'id_order' => $order->id,
                'reason' => $request->reason,
                'refund_amount' => $order->total_amount,
                'refund_quantity' => $order->order_details->sum('quantity'),
                'status' => 'Đang chờ xử lý'
            ]);
        }
        return redirect()->back()->with('warning', 'Đang chờ xác nhận hoàn hàng từ shop.');
    }
}
