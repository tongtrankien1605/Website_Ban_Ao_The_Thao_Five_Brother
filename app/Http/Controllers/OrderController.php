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



    // Order Details
    public function getOrderDetails($id)
    {
        $order = Order::where('id', $id)
            ->with(['orderDetails' => function ($query) {
                $query->join('skuses', 'skuses.id', '=', 'order_details.id_product_variant')
                    ->select('order_details.*', 'skuses.name');
            }])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'id' => $order->id,
            'date' => $order->created_at->format('d/m/Y'),
            'status' => $order->payment_method_status_name,
            'total' => number_format($order->total_amount),
            'items' => $order->orderDetails->map(function ($item) {
                return [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => number_format($item->price),
                ];
            }),
        ]);
    }
}
