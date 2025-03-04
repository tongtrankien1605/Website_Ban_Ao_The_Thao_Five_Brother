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
        $address_user = AddressUser::where('id_user', Auth::id())->get();
        $cart = Cart::firstOrCreate(['id_user' => Auth::id()]);
        $cartItem = CartItem::where('id_user', Auth::id())->with('skuses')->get();
        $shipping = ShippingMethod::all();
        $paymentMethods = PaymentMethod::all();
    
        $total = 0;
        foreach ($cartItem as $item) {
            $total += $item->price * $item->quantity;
        }
        // dd($total);
        // dd($Address);
        // $address_user = $address_user->toArray();
        // dd($address_user);
        return view('client.checkout', compact('address_user', 'cartItem', 'cart', 'total', 'shipping','paymentMethods'));
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
        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_Url = config('services.vnpay.url');
    
        // ✅ Chuyển total_amount thành số nguyên (VNPay chỉ nhận đơn vị VND x100)
        $vnp_Amount = intval($order->total_amount) * 100;
    
        // ✅ Đảm bảo `TxnRef` là số nguyên duy nhất
        $vnp_TxnRef = strval($order->id);
    
        // ✅ Dùng giá trị hợp lệ từ VNPay Docs (có thể thử `billpayment`)
        $vnp_OrderType = "billpayment"; 
    
        // ✅ Kiểm tra lại thời gian để đúng định dạng VNPay
        $vnp_CreateDate = now()->format('YmdHis');
    
        // ✅ Fix lỗi `vnp_ReturnUrl` (loại bỏ dấu `//`)
        $vnp_ReturnUrl = rtrim(env('APP_URL'), '/') . "/payment/vnpay/callback";
    
        // ✅ Thêm đầy đủ thông tin theo chuẩn VNPay
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CurrCode" => "VND",
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_OrderInfo" => "Thanh toán đơn hàng #" . $order->id,
            "vnp_OrderType" => $vnp_OrderType, // ✅ Fix lỗi OrderType
            "vnp_IpAddr" => request()->ip(),
            "vnp_CreateDate" => $vnp_CreateDate, // ✅ Fix lỗi ngày
            "vnp_ReturnUrl" => $vnp_ReturnUrl // ✅ Fix lỗi Localhost
        ];
    
        // ✅ Sắp xếp tham số & tạo chữ ký bảo mật (HMAC SHA512)
        ksort($inputData);
        $query = http_build_query($inputData);
        $hashData = urldecode($query);
        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    
        // ✅ Gửi dữ liệu đến VNPay
        $vnp_Url .= '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;
        dd($vnp_Url);
    
        return redirect($vnp_Url);
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