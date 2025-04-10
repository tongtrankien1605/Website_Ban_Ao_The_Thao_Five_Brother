<?php

namespace App\Http\Controllers;

use App\Models\AddressUser;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Inventory;
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
use App\Models\User;
use App\Models\VoucherUser;

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
        // dd($total, $saleTotal);
        $code = $request->input('code');

        // dd($code);
        $userVouchers = VoucherUser::where('id_user', Auth::id())->with('vouchers')
            ->get();
        // dd($userVouchers->toArray());

        // if ($voucher) {
        //     $voucherUser = DB::table('voucher_user')->where([['id_voucher', $voucher->id], ['id_user', Auth::id()]])->first();
        //     if ($voucherUser) {
        //         return response()->json(['message' => 'Mã giảm giá đã được sử dụng'], 500);
        //     }
        //     if ($voucher->start_date > now()) {
        //         return response()->json(['message' => 'Mã giảm giá chưa đến thời gian sử dụng'], 500);
        //     }
        //     if ($voucher->end_date < now()) {
        //         return response()->json(['message' => 'Mã giảm giá đã hết hạn'], 500);
        //     }
        // }
        // if ($voucher && $voucher->status == 0) {
        //     if ($voucher->quantity > 0) {
        //         $voucher->quantity -= 1;
        //         $voucher->save();
        //     } else {
        //         return response()->json(['message' => 'Mã giảm giá đã hết lượt sử dụng'], 500);
        //     }
        // } else {
        //     return response()->json(['message' => 'Mã giảm giá không hợp lệ'], 500);
        // }
        $address_user = AddressUser::where('id_user', Auth::id())->get();
        // dd($address_user->toArray());
        $shipping_methods = ShippingMethod::all();
        // dd($shipping_methods->toArray());
        $paymentMethods = PaymentMethod::whereNot('id_payment_method', 2)->get();

        $cartItem = collect(); // Tạo danh sách rỗng

        if ($selectedItems) {
            $cartItem = CartItem::whereIn('id', collect($selectedItems)->pluck('id'))
                ->where('id_user', Auth::id())
                ->with('skuses')
                ->get();
        }


        return view('client.checkout', compact(
            [
                'address_user',
                'cartItem',
                'new_total',
                'shipping_methods',
                'paymentMethods',
                'total',
                'saleTotal',
                'userVouchers'
            ]
        ));
    }

    // CheckoutController.php
    public function apply(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $user = auth()->user();

        // Lấy bản ghi từ bảng trung gian voucher_user
        $userVoucher = VoucherUser::where('id_user', $user->id)
            ->where('id_voucher', $request->voucher_id)
            ->with('vouchers') // Eager load chi tiết voucher
            ->first();

        if (!$userVoucher) {
            return response()->json(['message' => 'Voucher không hợp lệ hoặc không thuộc về bạn.'], 400);
        }

        if ($userVoucher->used_at !== null) {
            return response()->json(['message' => 'Voucher đã được sử dụng.'], 400);
        }

        $voucher = $userVoucher->vouchers;
        $now = now();

        if ($now->lt($voucher->start_date) || $now->gt($voucher->end_date)) {
            return response()->json(['message' => 'Voucher đã hết hạn hoặc chưa kích hoạt.'], 400);
        }

        if ($voucher->status != 0) {
            return response()->json(['message' => 'Voucher hiện không hoạt động.'], 400);
        }

        $subtotal = $request->subtotal;
        $discount = 0;

        if ($voucher->discount_type === 'percentage') {
            $discount = $subtotal * ($voucher->discount_value / 100);
        } else {
            $discount = $voucher->discount_value;
        }

        if ($voucher->max_discount_amount && $discount > $voucher->max_discount_amount) {
            $discount = $voucher->max_discount_amount;
        }

        $final_total = $subtotal - $discount;

        return response()->json([
            'voucher_discount' => round($discount),
            'final_total' => round($final_total),
            'message' => 'Áp dụng voucher thành công.',
        ]);
    }
    


    public function processPayment(Request $request, $order)
    {
        // dd($order);
        if ($request->payment_method_id == 2) {
            return $this->processPayPal($order);
        } elseif ($request->payment_method_id == 3) {
            return $this->processVNPay($order);
        }

        return back()->withErrors('Phương thức thanh toán không hợp lệ.');
    }

    public function processVNPay($order)
    {
        // dd($order);
        // Lấy tất cả sản phẩm trong giỏ hàng của user hiện tại
        $cartItems = CartItem::where('id_user', Auth::id())->get();
        
        // Kiểm tra nếu giỏ hàng trống
        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống');
        }
    
        // Lấy danh sách id các biến thể sản phẩm từ giỏ hàng
        $productVariantIds = $cartItems->pluck('id_product_variant')->toArray();
        
        // Lấy thông tin tồn kho của các sản phẩm trong giỏ
        // dd($inventory->toArray());
        // dd($cartItem->toArray());
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
            // dd(1);
            // dd($order);
            return response()->json([
                'redirect_url' => $vnp_Url
            ]);
        } else {
            $order->id_payment_method_status = 2;
            $order->save();
            // dd($order);
           foreach ($cartItems as $items) {
                $inventories = Inventory::where('id', $items->id_product_variant)->first();
                if ($inventories) {
                    $inventories->quantity -= $items->quantity;
                    $inventories->save();
                }
            }
            $cartItems->each(function ($item) {
             $item->delete();
            });
            // dd($order);
            return response()->json([
                'redirect_url' => $vnp_Url
            ]);
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
