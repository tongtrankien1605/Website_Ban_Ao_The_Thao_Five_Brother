<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $cart = Cart::firstOrCreate(['id_user' => Auth::id()]);
        $cartItem = CartItem::where('id_user', Auth::id())->with('skuses')->get();
        $total = 0;
        foreach ($cartItem as $item) {
            $total += $item->price * $item->quantity;
        }
        // dd($request->all(),$total);

        // dd($cartItem->toArray());
        $order = Order::create([
            'id_user' => Auth::id(),
            'id_address' => $request->address_id,
            'phone_number' => $request->phone_number,
            'id_shipping_method' => $request->shipping_id,
            'id_payment_method' => $request->payment_method,
            'total_amount' => $total,
            'id_order_status' => 1, // Đơn hàng mới
        ]);


        foreach ($cartItem as $item) {
            OrderDetail::create([
                'id_order' => $order->id,
                'id_product_variant' => $item->id_product_variant,
                'quantity' => $item->quantity,
                'unit_price' => $item->skuses->price,
                'total_price' => $item->quantity * $item->skuses->price,
            ]);
        }

        // $cart->delete();

        if ($request->payment_method == 1) {
            $cart->delete();
            // $order->update(['id_order_status' => 2]); // "Đã thanh toán"
            return redirect()->route('order.success')->with('success', 'Đơn hàng của bạn sẽ được giao COD!');
        }

        $paymentController = new PaymentController();
        return $paymentController->processPayment($request, $order);
    }
}
