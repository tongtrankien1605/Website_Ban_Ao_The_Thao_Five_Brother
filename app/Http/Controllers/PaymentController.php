<?php

namespace App\Http\Controllers;

use App\Models\AddressUser;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $address_user = AddressUser::where('id_user', Auth::id())->get();
        $cart = Cart::firstOrCreate(['id_user' => Auth::id()]);
        $cartItem = CartItem::where('id_user', Auth::id())->with('skuses')->get();
        $shipping = ShippingMethod::all();
     
    
        $total = 0;
        foreach ($cartItem as $item) {
            $total += $item->price * $item->quantity;
        }
        // dd($total);
        $response = Http::get('https://provinces.open-api.vn/api/?depth=3');
        $locations = $response->json();
        // dd($Address);
        // $address_user = $address_user->toArray();
        // dd($address_user);
        return view('client.checkout', compact('address_user', 'cartItem', 'cart', 'total', 'shipping', 'locations'));
    }
    // public function getLocations($type, $id = null)
    // {
    //     $url = "https://provinces.open-api.vn/api/";

    //     if ($type == "province") {
    //         $url .= "?depth=1"; // Lấy danh sách tỉnh
    //     } elseif ($type == "district" && $id) {
    //         $url .= "p/$id?depth=2"; // Lấy danh sách huyện theo tỉnh
    //     } elseif ($type == "ward" && $id) {
    //         $url .= "d/$id?depth=2"; // Lấy danh sách xã theo huyện
    //     } else {
    //         return response()->json([]); // Trả về rỗng nếu sai
    //     }

    //     $response = Http::get($url);
    //     return response()->json($response->json());
    // }

    // public function processPayment(Request $request)
    // {
    //     $request->validate([
    //         'order_id' => 'required|exists:orders,id',
    //         'payment_method' => 'required|string',
    //     ]);

    //     $order = Order::findOrFail($request->order_id);

    //     if ($request->payment_method == 'vnpay') {
    //         return $this->processVNPay($order);
    //     } elseif ($request->payment_method == 'paypal') {
    //         return $this->processPayPal($order);
    //     }

    //     return back()->withErrors('Phương thức thanh toán không hợp lệ.');
    // }

    // public function processVNPay($order)
    // {
    //     $vnp_TmnCode = config('services.vnpay.tmn_code');
    //     $vnp_HashSecret = config('services.vnpay.hash_secret');
    //     $vnp_Url = config('services.vnpay.url');

    //     $vnp_TxnRef = $order->id;
    //     $vnp_OrderInfo = "Thanh toán đơn hàng #" . $order->id;
    //     $vnp_Amount = $order->total_amount * 100;
    //     $vnp_Locale = "vn";
    //     $vnp_BankCode = "";
    //     $vnp_Returnurl = route('payment.vnpay.callback');

    //     $inputData = [
    //         "vnp_Version" => "2.1.0",
    //         "vnp_TmnCode" => $vnp_TmnCode,
    //         "vnp_Amount" => $vnp_Amount,
    //         "vnp_Command" => "pay",
    //         "vnp_CreateDate" => date('YmdHis'),
    //         "vnp_CurrCode" => "VND",
    //         "vnp_IpAddr" => request()->ip(),
    //         "vnp_Locale" => $vnp_Locale,
    //         "vnp_OrderInfo" => $vnp_OrderInfo,
    //         "vnp_OrderType" => "other",
    //         "vnp_ReturnUrl" => $vnp_Returnurl,
    //         "vnp_TxnRef" => $vnp_TxnRef
    //     ];

    //     ksort($inputData);
    //     $query = http_build_query($inputData);
    //     $hashData = urldecode($query);
    //     $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    //     $query .= '&vnp_SecureHash=' . $vnpSecureHash;
    //     $vnp_Url .= '?' . $query;

    //     return redirect($vnp_Url);
    // }

    // public function processPayPal($order)
    // {
    //     $provider = new PayPalClient;
    //     $provider->setApiCredentials(config('services.paypal'));

    //     $response = $provider->createOrder([
    //         "intent" => "CAPTURE",
    //         "purchase_units" => [
    //             [
    //                 "amount" => [
    //                     "currency_code" => "USD",
    //                     "value" => $order->total_amount
    //                 ]
    //             ]
    //         ],
    //         "application_context" => [
    //             "return_url" => route('payment.paypal.success'),
    //             "cancel_url" => route('payment.paypal.cancel'),
    //         ]
    //     ]);

    //     if (isset($response['id']) && $response['status'] == "CREATED") {
    //         foreach ($response['links'] as $link) {
    //             if ($link['rel'] == 'approve') {
    //                 return redirect($link['href']);
    //             }
    //         }
    //     }

    //     return back()->withErrors('Lỗi khi tạo thanh toán PayPal.');
    // }

    // public function vnpayCallback(Request $request)
    // {
    //     if ($request->vnp_ResponseCode == "00") {
    //         $order = Order::findOrFail($request->vnp_TxnRef);
    //         $order->update(['id_order_status' => 2]);

    //         PayMent::create([
    //             'id_order' => $order->id,
    //             'id_user' => $order->id_user,
    //             'payment_method' => 'vnpay',
    //             'amount' => $order->total_amount,
    //             'transaction_id' => $request->vnp_TransactionNo,
    //             'status' => 'completed',
    //         ]);

    //         return redirect()->route('order.success')->with('success', 'Thanh toán VNPay thành công!');
    //     }

    //     return redirect()->route('checkout')->withErrors('Thanh toán VNPay thất bại.');
    // }

    // public function paypalSuccess(Request $request)
    // {
    //     $provider = new PayPalClient;
    //     $provider->setApiCredentials(config('services.paypal'));

    //     $response = $provider->capturePaymentOrder($request->token);

    //     if ($response['status'] == "COMPLETED") {
    //         $order = Order::findOrFail(Session::get('order_id'));
    //         $order->update(['id_order_status' => 2]);

    //         Payment::create([
    //             'id_order' => $order->id,
    //             'id_user' => $order->id_user,
    //             'payment_method' => 'paypal',
    //             'amount' => $order->total_amount,
    //             'transaction_id' => $response['id'],
    //             'status' => 'completed',
    //         ]);

    //         return redirect()->route('order.success')->with('success', 'Thanh toán PayPal thành công!');
    //     }

    //     return redirect()->route('checkout')->withErrors('Thanh toán PayPal thất bại.');
    // }

}
