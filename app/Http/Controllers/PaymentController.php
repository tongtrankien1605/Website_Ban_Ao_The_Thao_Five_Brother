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
        try {
            $user = auth()->user();
            Log::info('User starting payment process:', ['user_id' => $user->id]);

            $selectedItems = $request->input('items');
            $new_total = $request->input('new_total', 0);
            $total = $request->input('total');
            $saleTotal = $total - $new_total;
            $code = $request->input('code');

            // Lấy dữ liệu cần thiết
            $userVouchers = VoucherUser::where('id_user', $user->id)->with('vouchers')->get();
            $address_user = AddressUser::where('id_user', $user->id)->get();
            $shipping_methods = ShippingMethod::all();
            $paymentMethods = PaymentMethod::whereNot('id_payment_method', 2)->get();

            $cartItem = collect();
            if ($selectedItems) {
                $cartItem = CartItem::whereIn('id', collect($selectedItems)->pluck('id'))
                    ->where('id_user', $user->id)
                    ->with('skuses')
                    ->get();
            }

            // Lưu thông tin đơn hàng vào session
            $orderData = [
                'user_id' => $user->id,
                'items' => $selectedItems,
                'total' => $total,
                'new_total' => $new_total,
                'sale_total' => $saleTotal,
                'code' => $code,
                'expires_at' => now()->addMinutes(15)
            ];
            session(['pending_order' => $orderData]);
            Log::info('Order data stored in session:', ['session_data' => session('pending_order')]);

            // Kiểm tra số lần thử thất bại
            if ($user->failed_attempts >= 3) {
                Log::warning('User locked due to too many failed attempts:', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản của bạn đã bị khóa do quá nhiều lần thử thất bại. Vui lòng thử lại sau 15 phút.'
                ], 403);
            }

            // Tạo payment attempt mới
            $paymentAttempt = PaymentAttempt::create([
                'user_id' => $user->id,
                'started_at' => now(),
                'expires_at' => now()->addMinutes(15),
                'is_completed' => false
            ]);

            Log::info('Payment attempt created:', ['attempt_id' => $paymentAttempt->id]);

            // Trả về view với dữ liệu
            return view('client.checkout', compact(
                'address_user',
                'cartItem',
                'new_total',
                'shipping_methods',
                'paymentMethods',
                'total',
                'saleTotal',
                'userVouchers'
            ));

        } catch (\Exception $e) {
            Log::error('Payment process error:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi bắt đầu thanh toán'
            ], 500);
        }
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

 
}
