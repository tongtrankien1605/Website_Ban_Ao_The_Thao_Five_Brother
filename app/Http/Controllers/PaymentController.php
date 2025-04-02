<?php

namespace App\Http\Controllers;

use App\Models\AddressUser;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentAttempt;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        $selectedItems = $request->input('items'); // Nhận danh sách sản phẩm từ AJAX
        $new_total = $request->input('new_total'); // Nhận new_total từ URL (mặc định = 0)
        // dd($new_total);

        $total = $request->input('total');
        $saleTotal = $total - $new_total;
        $code = $request->input('code');

        // dd($discount);
        $voucher = Voucher::where([['status', 0], ['code',$code]])->first();
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

        // Nếu new_total không có, tính lại tổng tiền từ giỏ hàng
        // if ($new_total == 0) {
        //     $new_total = 0;
        //     foreach ($cartItem as $item) {
        //         $new_total += $item->price * $item->quantity;
        //     }
        // }

        return view('client.checkout', compact(
            [
                'address_user',
                'cartItem',
                'new_total',
                'shipping',
                'paymentMethods',
                'total',
                'saleTotal',
                'voucher'
            ]
        ));
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
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials([
                'mode' => config('services.paypal.mode'),
                'client_id' => config('services.paypal.sandbox.client_id'),
                'client_secret' => config('services.paypal.sandbox.client_secret'),
            ]);

            // Get access token
            $provider->getAccessToken();

            // Create PayPal order
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => config('services.paypal.currency'),
                            "value" => number_format($order->total_amount, 2)
                        ],
                        "description" => "Order #" . $order->id,
                        "items" => [
                            [
                                "name" => "Order #" . $order->id,
                                "quantity" => "1",
                                "unit_amount" => [
                                    "currency_code" => config('services.paypal.currency'),
                                    "value" => number_format($order->total_amount, 2)
                                ]
                            ]
                        ]
                    ]
                ],
                "application_context" => [
                    "return_url" => route('payment.paypal.success'),
                    "cancel_url" => route('payment.paypal.cancel'),
                    "brand_name" => config('app.name'),
                    "landing_page" => "NO_PREFERENCE",
                    "user_action" => "PAY_NOW"
                ]
            ]);

            // Check if order was created successfully
            if (isset($response['id']) && $response['status'] == "CREATED") {
                // Find the approval URL
                foreach ($response['links'] as $link) {
                    if ($link['rel'] == 'approve') {
                        // Update order status
                        $order->id_payment_method_status = 2; // Payment pending
                        $order->save();
                        
                        // Redirect to PayPal
                        return redirect($link['href']);
                    }
                }
            }

            throw new \Exception('Failed to create PayPal order');

        } catch (\Exception $e) {
            // Log the detailed error
            Log::error('PayPal Payment Error', [
                'order_id' => $order->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update order status to failed
            $order->id_payment_method_status = 1; // Payment failed
            $order->save();

            // Check for specific error types
            if (str_contains($e->getMessage(), 'INVALID_CLIENT')) {
                return back()->withErrors('PayPal configuration error: Invalid client credentials. Please check your PayPal settings.');
            } elseif (str_contains($e->getMessage(), 'INVALID_REQUEST')) {
                return back()->withErrors('Invalid request to PayPal. Please try again.');
            } elseif (str_contains($e->getMessage(), 'INVALID_CURRENCY')) {
                return back()->withErrors('Invalid currency code. Please check your PayPal configuration.');
            } elseif (str_contains($e->getMessage(), 'INVALID_AMOUNT')) {
                return back()->withErrors('Invalid amount format. Please check the order total.');
            } elseif (str_contains($e->getMessage(), 'CERTIFICATE')) {
                return back()->withErrors('SSL certificate error. Please check your server configuration.');
            } else {
                return back()->withErrors('Payment processing failed: ' . $e->getMessage());
            }
        }
    }

    public function paypalSuccess(Request $request)
    {
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials([
                'mode' => config('services.paypal.mode'),
                'client_id' => config('services.paypal.sandbox.client_id'),
                'client_secret' => config('services.paypal.sandbox.client_secret'),
            ]);

            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request->token);

            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                // Find the order and update its status
                $order = Order::where('id', $response['purchase_units'][0]['description'])->first();
                if ($order) {
                    $order->id_payment_method_status = 2; // Payment completed
                    $order->id_order_status = 2; // Order confirmed
                    $order->save();

                    // Clear cart items
                    CartItem::where('id_user', Auth::id())->delete();

                    return redirect()->route('order_success')->with('success', 'Payment completed successfully!');
                }
            }

            return redirect()->route('checkout')->withErrors('Payment verification failed.');
        } catch (\Exception $e) {
            Log::error('PayPal Success Error', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout')->withErrors('Payment verification failed: ' . $e->getMessage());
        }
    }

    public function paypalCancel()
    {
        return redirect()->route('checkout')->withErrors('Payment was cancelled.');
    }

 
}
