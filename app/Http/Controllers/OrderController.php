<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\InventoryEntry;
use App\Models\OrderStatusHistory;
use App\Models\PaymentAttempt;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = User::find(Auth::id());
        if (!$user) {
            return redirect()->route('login')->withErrors('Bạn cần đăng nhập để đặt hàng.');
        }
        
        if ($user->is_locked && $user->locked_until > now()) {
            return redirect()->route('cart.index')->withErrors('Tài khoản của bạn đã bị khóa. Vui lòng thử lại sau ' . $user->locked_until->format('H:i:s'));
        }
    
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
    
        DB::beginTransaction(); // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
    
        try {
            $order = Order::create([
                'id_user' => $user->id,
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
                    'unit_price' => $item->price,
                    'total_price' => $item->quantity * $item->price,
                ]);
            }
    
            // 🔹 Kiểm tra xem đã có PaymentAttempt chưa (tránh tạo trùng)
            $existingAttempt = PaymentAttempt::where('user_id', $user->id)
                ->where('order_id', $order->id)
                ->where('is_completed', false)
                ->first();
    
            if (!$existingAttempt) {
                // Nếu chưa có, tạo mới
                $paymentAttempt = PaymentAttempt::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'started_at' => now(),
                    'expires_at' => now()->addMinutes(1),
                    'is_completed' => false
                ]);
            }
    
            // 🔹 Kiểm tra nếu tài khoản cần bị khóa
            if ($user->failed_attempts >= 3) {
                $user->is_locked = true;
                $user->locked_until = now()->addMinutes(15);
                $user->save();
            }
    
            DB::commit(); // ✅ Lưu thay đổi nếu mọi thứ đều ổn
    
            // 🔹 Nếu là COD, xóa cart và chuyển hướng
            if ($request->payment_method == 1) {
                CartItem::whereIn('id', $request->cart_item_ids)->delete();
                return redirect()->route('order_success')->with('success', 'Đơn hàng của bạn sẽ được giao COD!');
            }
    
            // 🔹 Nếu là thanh toán online, chuyển sang PaymentController xử lý
            $paymentController = new PaymentController();
            return $paymentController->processPayment($request, $order);
    
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Hoàn tác nếu có lỗi
            return redirect()->route('cart.index')->withErrors('Lỗi khi đặt hàng: ' . $e->getMessage());
        }
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
    public function lockAccount(Request $request)
    {
        try {
            $user = User::find(Auth::id());
            if (!$user) {
                return response()->json(['message' => 'Bạn chưa đăng nhập!'], 401);
            }

            // Cập nhật trạng thái tài khoản
            $user->is_locked = true;
            $user->locked_until = now()->addMinutes(1); // Khóa trong 15 phút
            $user->update();
            $user->save();

            Log::info("Tài khoản bị khóa: " . $user->email);

            return response()->json([
                'message' => 'Tài khoản của bạn đã bị khóa do quá nhiều lần thử thất bại. Vui lòng thử lại sau 15 phút.',
                'attempts_remaining' => 0,
                'redirect' => route('show.cart')
            ]);
        } catch (\Exception $e) {
            Log::error("Lỗi khóa tài khoản: " . $e->getMessage());
            return response()->json(['message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.'], 500);
        }
    }

    public function createPaymentAttempt(Request $request)
    {
        Log::info('🔴 Request received in createPaymentAttempt', ['user_id' => Auth::id()]);
    
        try {
            $user = User::find(Auth::id());
            if (!$user) {
                Log::warning('🔴 User not found', ['user_id' => Auth::id()]);
                return response()->json(['message' => 'Bạn chưa đăng nhập!'], 401);
            }
            if ($user->is_locked && $user->locked_until > now()) {
                Log::warning('🔴 User is locked', ['user_id' => Auth::id()]);
                return response()->json(['message' => 'Tài khoản của bạn đã bị khóa. Vui lòng thử lại sau ' . $user->locked_until->format('H:i:s')], 403);
            }
            Log::info('🟢 User ID:', ['id' => $user->id]);
    
            if ($user->failed_attempts = 3) {
                $user->is_locked = true;
                $user->locked_until = now()->addMinutes(15);
                $user->save();
                Log::warning('🚨 User bị khóa', ['user_id' => $user->id]);
                # code...
            }
    
            $paymentAttempt = PaymentAttempt::create([
                'user_id' => Auth::id(),
                'order_id' => null,
                'started_at' => now(),
                'expires_at' => now()->addMinutes(1),
                'is_completed' => false
            ]);
    
            Log::info('🟢 Payment attempt created', ['id' => $paymentAttempt->id]);
    
            $user->increment('failed_attempts');
            $user->save();


    
            return response()->json([
                'message' => 'Payment attempt created successfully',
                'attempts_remaining' => max(0, 3 - $user->failed_attempts),
                'redirect' => route('show.cart')
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Lỗi trong createPaymentAttempt', ['error' => $e->getMessage()]);
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}


