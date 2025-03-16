<?php

namespace App\Http\Controllers;

use App\Models\AddressUser;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function index(Request $request)
{
    $selectedItems = $request->input('items'); // Lấy danh sách sản phẩm từ AJAX
    $address_user = AddressUser::where('id_user', Auth::id())->get();
    $shipping = ShippingMethod::all();
    $paymentMethods = PaymentMethod::all();

    $cartItem = collect(); // Tạo danh sách rỗng

    if ($selectedItems) {
        $cartItem = CartItem::whereIn('id', collect($selectedItems)->pluck('id'))
            ->where('id_user', Auth::id())
            ->with('skuses')
            ->get();
    }

    $total = 0;
    foreach ($cartItem as $item) {
        $total += $item->price * $item->quantity;
    }

    return view('client.checkout', compact('address_user', 'cartItem', 'total', 'shipping', 'paymentMethods'));
}


    public function processPayment(Request $request, $order)
    {
        if ($request->payment_method == 2) {
            return $this->processPayPal($order);
        } elseif ($request->payment_method == 3) {
            return $this->processVNPay($order);
        }

        return back()->withErrors('Phương thức thanh toán không hợp lệ.');
    }

    public function processVNPay($order)
    {
        // dd($order);
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = env('VNP_RETURN_URL');
        $vnp_TmnCode = env('VNP_TMN_CODE');
        $vnp_HashSecret = env('VNP_HASH_SECRET');

        $vnp_TxnRef = $order->id; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán hóa đơn";
        $vnp_OrderType = "test";
        $vnp_Amount = intval($order->total_amount) * 100;
        $vnp_Locale = "VN";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        if (isset($_POST['redirect'])) {
            $order->id_payment_method_status = 2;
            $order->save();
                // dd($order);
                return redirect()->away($vnp_Url);
        } else {
            $order->id_payment_method_status = 2;
            $order->save();
                // dd($order);
            return redirect()->away($vnp_Url);
        }

    }


    public function processPayPal($order)
    {
        // ✅ Khởi tạo PayPal SDK với config từ Laravel
        // dd(config('services.paypal'));
        dd(new PayPalClient());
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('services.paypal'));

        // ✅ Kiểm tra lại xem có client_id không
        $config = config('services.paypal');
        if (empty($config['client_id'])) {
            return back()->withErrors('PayPal Client ID bị thiếu! Kiểm tra lại .env.');
        }


        // ✅ Tạo đơn hàng PayPal
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $order->total_amount
                    ]
                ]
            ],
            "application_context" => [
                "return_url" => route('payment.paypal.success'),
                "cancel_url" => route('payment.paypal.cancel'),
            ]
        ]);

        // ✅ Kiểm tra response từ PayPal
        if (isset($response['id']) && $response['status'] == "CREATED") {
            foreach ($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return redirect($link['href']);
                }
            }
        }

        return back()->withErrors('Lỗi khi tạo thanh toán PayPal.');
    }
}
