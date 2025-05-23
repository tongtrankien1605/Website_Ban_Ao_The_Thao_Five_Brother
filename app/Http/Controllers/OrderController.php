<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Inventory;
use App\Models\InventoryEntry;
use App\Models\InventoryLog;
use App\Models\OrderDispute;
use App\Models\OrderHistory;
use App\Models\OrderStatusHistory;
use App\Models\PaymentAttempt;
use App\Models\Refund;
use App\Models\User;
use App\Models\VoucherUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LengthException;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $variantId = collect(session('pending_order')['items'])->pluck('id');
            $cartItemdelete = CartItem::whereIn('id', $variantId)->get()->keyBy('id');

            $user = auth()->user();
            $paymentMethodId = (int) $request->input('payment_method_id');

            // Check if user has too many failed attempts
            if ($user->failed_attempts >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản của bạn đã bị khóa do quá nhiều lần thử thất bại. Vui lòng thử lại sau 15 phút.'
                ], 403);
            }

            // Kiểm tra số lượng tồn kho
            $outOfStockItems = [];
            $variantIds = collect($request->items)->pluck('id');
            $cartItems = CartItem::whereIn('id', $variantIds)
                ->with('skuses')
                ->get()
                ->keyBy('id');

            $inventories = Inventory::whereIn('id_product_variant', $cartItems->pluck('id_product_variant'))
                ->get()
                ->keyBy('id_product_variant');

            foreach ($request->items as $item) {
                $cartItem = $cartItems->get($item['id']);
                $variantId = $cartItem->id_product_variant;
                $inventory = $inventories->get($variantId);
                $quantity = (int) $item['quantity'];
                // dd($quantity);

                if ($inventory->quantity == 1) {
                    $outOfStockItems[] = [
                        'id' => $cartItem->id,
                        'name' => $cartItem->skuses->name,
                        'variant_name' => $cartItem->skuses->name,
                        'requested_quantity' => $quantity,
                        'available_quantity' => $inventory->quantity
                    ];
                }
                // dd($outOfStockItems);
            }


            // if (!empty($outOfStockItems)) {
            //     DB::rollBack();
            //     broadcast(new \App\Events\ProductOutOfStock($outOfStockItems))->toOthers();
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Một số sản phẩm đã hết hàng', 
            //         'out_of_stock_items' => $outOfStockItems
            //     ], 400);
            // }



            $order = Order::create([
                'id_user' => $user->id,
                'receiver_name' => $request->name,
                'phone_number' => $request->phone,
                'address' => $request->address,
                'id_shipping_method' => $request->shipping_method_id,
                'id_payment_method' => $paymentMethodId,
                'id_voucher' => $request->voucher_id,
                'total_amount' => $request->total,
                'id_order_status' => 1,
                'id_payment_method_status' => 1,
            ]);
            // dd($order->toArray());



            // Create order details and temporarily reserve inventory
            foreach ($request->items as $item) {
                $cartItem = $cartItems->get($item['id']);
                $variantId = $cartItem->id_product_variant;
                $inventory = $inventories->get($variantId);
                $quantity = (int) $item['quantity'];

                OrderDetail::create([
                    'id_order' => $order->id,
                    'id_product_variant' => $variantId,
                    'quantity' => $quantity,
                    'message' => $request->message ?? null,
                    'unit_price' => $item['price'],
                    'total_price' => $quantity * $item['price'],
                ]);

                // Create inventory log for temporary reservation
                InventoryLog::create([
                    'id_product_variant' => $variantId,
                    'old_quantity' => $inventory->quantity,
                    'new_quantity' => $inventory->quantity - $quantity,
                    'change_quantity' => $quantity,
                    'reason' => 'Tạm giữ hàng cho đơn hàng #' . $order->id,
                    'type' => 'Tạm giữ',
                    'quantity' => $quantity,
                    'action' => 'order_reservation',
                    'user_id' => $user->id,
                ]);

                // Temporarily decrease inventory
                $inventory->decrement('quantity', $quantity);

                // dd($inventory->toArray());
                // Broadcast inventory update
                // dd($cartItem->skuses->name);

                // broadcast(new \App\Events\InventoryUpdated([
                //     'variant_id' => $variantId,
                //     'new_quantity' => $inventory->quantity,
                //     'product_name' => $cartItem->skuses->name,
                // ]))->toOthers();
            }

            // Create payment attempt record
            $paymentAttempt = PaymentAttempt::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'started_at' => now(),
                'expires_at' => now()->addMinutes(15),
                'is_completed' => false
            ]);

            if ($paymentMethodId === 1) {
                // For cash on delivery, we can finalize the order immediately
                session()->forget('pending_order');
                foreach ($request->items as $item) {
                    $cartItem = $cartItems->get($item['id']);
                    $variantId = $cartItem->id_product_variant;
                    $inventory = $inventories->get($variantId);
                    $quantity = (int) $item['quantity'];

                    // Update inventory log to show final sale
                    InventoryLog::create([
                        'id_product_variant' => $variantId,
                        'old_quantity' => $inventory->quantity,
                        'new_quantity' => $inventory->quantity,
                        'change_quantity' => 0,
                        'reason' => 'Xác nhận đơn hàng COD #' . $order->id,
                        'type' => 'Xác nhận',
                        'quantity' => 0,
                        'action' => 'order_confirmed',
                        'user_id' => $user->id,
                    ]);
                }

                // Mark payment attempt as completed
                $paymentAttempt->update(['is_completed' => true]);

                // Clear cart
                $cartItemdelete->each(function ($item) {
                    $item->delete();
                });

                DB::commit();

                return response()->json(['success' => true, 'message' => 'Đặt hàng thành công!']);
            } else {
                // For online payment methods
                $cartItemdelete->each(function ($item) {
                    $item->delete();
                });
                DB::commit();
                $paymentController = new PaymentController();
                return $paymentController->processPayment($request, $order);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error('Order Store Error: ' . $e->getMessage());
            return response()->json(['error' => true, 'message' => 'Đã xảy ra lỗi, vui lòng thử lại.']);
        }
    }

    public function update(Request $request, $orderId)
    {
        $order = Order::with('order_details')->findOrFail($orderId);

        $oldStatus = $order->id_order_status;

        $newStatus = $request->id_order_status;

        if ($newStatus == OrderStatus::SUCCESS) {

            $order->update(['id_payment_method_status' => 2, 'id_order_status' => $newStatus]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => 'Khách hàng đã nhận hàng',
            ]);
            return redirect()->back()->with('success', '5 Brother cảm ơn vì bạn đã mua hàng.');
        } elseif ($newStatus == OrderStatus::CANCEL) {
            if ($order->id_payment_method_status == 1 || $order->id_payment_method_status == 4) {
                $validatedData = $request->validate([
                    'reason' => 'required|max:255'
                ]);
                $order->update(['id_order_status' => $newStatus]);
                OrderHistory::create([
                    'order_id' => $order->id,
                    'note_user' => $validatedData['reason'],
                    'status' => 'Đã xử lý',
                ]);
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'note' => 'Khách hàng đã hủy đơn hàng',
                ]);
                foreach ($order->order_details as $item) {
                    $inventory = Inventory::where('id_product_variant', $item->id_product_variant)->first();
                    $oldQuantity = $inventory->quantity;
                    $inventory->quantity += $item->quantity;
                    $inventory->save();
                    InventoryLog::create([
                        'id_product_variant' => $item->id_product_variant,
                        'user_id' => Auth::id(),
                        'old_quantity' => $oldQuantity,
                        'new_quantity' => $inventory->quantity,
                        'change_quantity' => $item->quantity,
                        'reason' => "Khách hàng hủy đơn",
                    ]);
                }
                return redirect()->back()->with('success', 'Hủy đơn thành công');
            } else {
                $validatedData = $request->validate([
                    'bank_account' => 'required|regex:/^\d+$/|max:25',
                    'bank_name' => 'required|string',
                    'account_holder_name' => 'required|string',
                    'reason' => 'required|max:255'
                ]);

                OrderHistory::create([
                    'order_id' => $order->id,
                    // 'old_status' => $oldStatus,
                    'note_user' => $validatedData['reason'],
                    // 'refund_amount' => $order->total_amount,
                    // 'refund_quantity' => $order->order_details->sum('quantity'),
                    'status' => 'Đang chờ xử lý',
                    'bank_account' => $validatedData['bank_account'],
                    'bank_name' => $validatedData['bank_name'],
                    'account_holder_name' => $validatedData['account_holder_name'],
                    // 'user_id' => Auth::id()
                ]);

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'note' => 'Đơn hàng đang chờ xử lý',
                ]);

                $order->update(['id_order_status' => OrderStatus::WAIT_REFUND]);

                return redirect()->back()->with('success', 'Thành công, vui lòng chờ xác nhận từ chúng tôi.');
            }
        } elseif ($newStatus == OrderStatus::REFUND) {
            $validatedData = $request->validate([
                'bank_account' => 'required|regex:/^\d+$/|max:25',
                'bank_name' => 'required|string',
                'account_holder_name' => 'required|string',
                'reason' => 'required|max:255',
                'evidence' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:51200',
            ]);

            $file = $request->file('evidence');
            $mime = $file->getMimeType();
            $isImage = str_starts_with($mime, 'image/');
            $isVideo = str_starts_with($mime, 'video/');

            // Lưu file
            $path = $file->store('refund_histories', 'public');

            Refund::create([
                'id_order' => $order->id,
                'old_status' => $oldStatus,
                'reason' => $validatedData['reason'],
                'refund_amount' => $order->total_amount,
                'refund_quantity' => $order->order_details->sum('quantity'),
                'status' => 'Đang chờ xử lý',
                'bank_account' => $validatedData['bank_account'],
                'bank_name' => $validatedData['bank_name'],
                'account_holder_name' => $validatedData['account_holder_name'],
                'image_path' => $isImage ? $path : null,
                'video_path' => $isVideo ? $path : null,
                'user_id' => Auth::id()
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => 'khách hàng đã yêu cầu hoàn hàng',
            ]);

            $order->update(['id_order_status' => $newStatus]);

            return redirect()->back()->with('success', 'Thành công, vui lòng chờ xác nhận từ chúng tôi.');
        }
        //  elseif ($newStatus == OrderStatus::FAILED) {
        //     $validatedData = $request->validate([
        //         'reason' => 'required|max:255',
        //     ]);

        //     Refund::create([
        //         'id_order' => $order->id,
        //         'old_status' => $oldStatus,
        //         'reason' => $validatedData['reason'],
        //         'refund_amount' => $order->total_amount,
        //         'refund_quantity' => $order->order_details->sum('quantity'),
        //         'status' => 'Đang chờ xử lý',
        //         'user_id' => Auth::id()
        //     ]);

        //     $order->update(['id_order_status' => $newStatus]);

        //     return redirect()->back()->with('success', 'Chúng tôi đã tiếp nhận yêu cầu của bạn. Vui lòng chờ phản hồi');
        // }
        elseif ($newStatus == OrderStatus::AUTHEN) {
            $validatedData = $request->validate([
                'reason' => 'required|max:255',
                'phone_number' => 'required|max:25',
            ]);

            OrderDispute::create([
                'order_id' => $order->id,
                'note' => $validatedData['reason'],
                'phone' => $validatedData['phone_number'],
                'customer_id' => Auth::id()
            ]);

            $order->update(['id_order_status' => $newStatus]);

            return redirect()->back()->with('success', 'Chúng tôi đã tiếp nhận thông tin và sẽ liên lạc với bạn trong thời gian sớm nhất');
        } elseif ($newStatus == OrderStatus::RETURN) {
            $order->update(['id_order_status' => $newStatus]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => 'Giao lại',
            ]);
            return redirect()->back()->with('success', 'Đơn hàng sẽ đến với bạn trong thời gian sớm nhất.');
        }
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
        // $cartItems = CartItem::where('id_user', Auth::id())->with('skuses')->get();
        // dd($cartItems->toArray());

        Log::info('🔴 Request received in createPaymentAttempt', ['user_id' => Auth::id()]);

        try {
            $user = User::find(Auth::id());
            if (!$user) {
                Log::warning('🔴 User not found', ['user_id' => Auth::id()]);
                return response()->json(['message' => 'Bạn chưa đăng nhập!'], 401);
            }

            // Check if user is already locked
            if ($user->is_locked && $user->locked_until > now()) {
                Log::warning('🔴 User is locked', ['user_id' => Auth::id()]);
                return response()->json([
                    'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng thử lại sau ' . $user->locked_until->format('H:i:s')
                ], 403);
            }

            Log::info('🟢 User ID:', ['id' => $user->id]);

            // Increment failed attempts
            $user->increment('failed_attempts');

            // Check if user has reached the maximum number of failed attempts
            if ($user->failed_attempts >= 3) {
                $user->is_locked = true;
                $user->locked_until = now()->addMinutes(15); // Lock for 15 minutes
                $user->save();
                Log::warning('🚨 User bị khóa', ['user_id' => $user->id]);
            }

            $paymentAttempt = PaymentAttempt::create([
                'user_id' => Auth::id(),
                'order_id' => null,
                'started_at' => now(),
                'expires_at' => now()->addMinutes(15), // 15 minutes to complete payment
                'is_completed' => false
            ]);

            Log::info('🟢 Payment attempt created', ['id' => $paymentAttempt->id]);

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

    public function checkStock(Request $request)
    {
        try {
            $user = auth()->user();
            Log::info('Checking stock for user:', ['user_id' => $user->id]);

            // Lấy thông tin đơn hàng từ session
            $pendingOrder = session('pending_order');
            if (!$pendingOrder || $pendingOrder['user_id'] != $user->id || now()->isAfter($pendingOrder['expires_at'])) {
                Log::warning('Invalid or expired session:', ['session_data' => $pendingOrder]);
                return response()->json([
                    'success' => false,
                    'message' => 'Phiên đặt hàng đã hết hạn'
                ], 400);
            }

            $selectedItems = $pendingOrder['items'];
            // dd($selectedItems);
            $cartItems = CartItem::whereIn('id', collect($selectedItems)->pluck('id'))
                ->where('id_user', $user->id)
                ->with('skuses')
                ->get();
            // dd($cartItems->toArray());
            $inventories = Inventory::whereIn('id_product_variant', $cartItems->pluck('id_product_variant'))
                ->get()
                ->keyBy('id_product_variant');

            $activePaymentAttempts = PaymentAttempt::where('is_completed', false)
                ->where('expires_at', '>', now())
                ->get();

            $pendingOrderIds = $activePaymentAttempts->pluck('order_id')->filter();

            $pendingOrderDetails = [];
            if ($pendingOrderIds->isNotEmpty()) {
                $pendingOrderDetails = OrderDetail::whereIn('id_order', $pendingOrderIds)
                    ->get()
                    ->groupBy('id_product_variant');
            }
            $outOfStock = [];
            foreach ($selectedItems as $item) {
                // $outOfStock = false;

                $cartItem = $cartItems->where('id', $item['id'])->first();
                if (!$cartItem) continue;

                $variantId = $cartItem->id_product_variant;
                $inventory = $inventories->get($variantId);
                // dd($inventory);
                $quantity = (int) $item['quantity'];

                if ($inventory) {
                    $stockQuantity = $inventory->quantity;

                    // Tính số lượng đã được đặt trước
                    $reservedQuantity = 0;
                    if (isset($pendingOrderDetails[$variantId])) {
                        $reservedQuantity = $pendingOrderDetails[$variantId]->sum('quantity');
                    }

                    $availableQuantity = $stockQuantity - $reservedQuantity;
                    // dd($availableQuantity);

                    if ($availableQuantity == $quantity) {
                        // dd(1);
                        $outOfStock[] = true;
                    }
                }
            }
            if (empty($outOfStock)) {
                $outOfStock[] = false;
            }
            return response()->json(['out_of_stock' => $outOfStock[0]]);
        } catch (\Exception $e) {
            Log::error('Stock check error:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null
            ]);
        }
    }

    public function handlePaymentFailure($orderId)
    {
        DB::beginTransaction();

        try {
            $order = Order::with('order_details')->findOrFail($orderId);
            $user = User::find($order->id_user);

            // Get payment attempt
            $paymentAttempt = PaymentAttempt::where('order_id', $orderId)
                ->where('is_completed', false)
                ->first();

            if (!$paymentAttempt) {
                Log::warning('Payment attempt not found for order: ' . $orderId);
                DB::rollBack();
                return false;
            }

            // Mark payment attempt as completed (failed)
            $paymentAttempt->update(['is_completed' => true]);

            // Get inventory records
            $inventories = Inventory::whereIn('id_product_variant', $order->order_details->pluck('id_product_variant'))
                ->get()
                ->keyBy('id_product_variant');

            // Refund inventory for each order detail
            foreach ($order->order_details as $detail) {
                $variantId = $detail->id_product_variant;
                $inventory = $inventories->get($variantId);
                $quantity = $detail->quantity;

                if ($inventory) {
                    // Create inventory log for refund
                    InventoryLog::create([
                        'id_product_variant' => $variantId,
                        'old_quantity' => $inventory->quantity,
                        'new_quantity' => $inventory->quantity + $quantity,
                        'change_quantity' => $quantity,
                        'reason' => 'Hoàn trả hàng do thanh toán thất bại cho đơn hàng #' . $orderId,
                        'type' => 'Hoàn trả',
                        'quantity' => $quantity,
                        'action' => 'payment_failure',
                        'user_id' => $user->id,
                    ]);

                    // Increment inventory
                    $inventory->increment('quantity', $quantity);
                }
            }

            // Update order status
            $order->update([
                'id_order_status' => 3, // Assuming 3 is the status for cancelled/failed orders
                'id_payment_method_status' => 3 // Assuming 3 is the status for failed payments
            ]);

            DB::commit();
            Log::info('Payment failure handled for order: ' . $orderId);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error handling payment failure: ' . $e->getMessage());
            return false;
        }
    }

    public function handlePaymentSuccess($orderId)
    {
        DB::beginTransaction();

        try {
            $order = Order::with('order_details')->findOrFail($orderId);
            $user = User::find($order->id_user);

            // Get payment attempt
            $paymentAttempt = PaymentAttempt::where('order_id', $orderId)
                ->where('is_completed', false)
                ->first();

            if (!$paymentAttempt) {
                Log::warning('Payment attempt not found for order: ' . $orderId);
                DB::rollBack();
                return false;
            }

            // Mark payment attempt as completed (success)
            $paymentAttempt->update(['is_completed' => true]);

            // Get inventory records
            $inventories = Inventory::whereIn('id_product_variant', $order->order_details->pluck('id_product_variant'))
                ->get()
                ->keyBy('id_product_variant');

            // Finalize inventory for each order detail
            foreach ($order->order_details as $detail) {
                $variantId = $detail->id_product_variant;
                $inventory = $inventories->get($variantId);
                $quantity = $detail->quantity;

                if ($inventory) {
                    // Create inventory log for final sale
                    InventoryLog::create([
                        'id_product_variant' => $variantId,
                        'old_quantity' => $inventory->quantity,
                        'new_quantity' => $inventory->quantity,
                        'change_quantity' => 0,
                        'reason' => 'Xác nhận đơn hàng #' . $orderId,
                        'type' => 'Xác nhận',
                        'quantity' => 0,
                        'action' => 'order_confirmed',
                        'user_id' => $user->id,
                    ]);
                }
            }

            // Update order status
            $order->update([
                'id_order_status' => 2, // Assuming 2 is the status for confirmed orders
                'id_payment_method_status' => 2 // Assuming 2 is the status for successful payments
            ]);

            // Clear cart
            CartItem::where('id_user', $user->id)->delete();

            // Reset failed attempts on successful payment
            $user->update(['failed_attempts' => 0]);

            DB::commit();
            Log::info('Payment success handled for order: ' . $orderId);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error handling payment success: ' . $e->getMessage());
            return false;
        }
    }


    public function handleExpiredPaymentAttempts()
    {
        try {
            // Get all expired payment attempts that haven't been marked as completed
            $expiredAttempts = PaymentAttempt::where('is_completed', false)
                ->where('expires_at', '<', now())
                ->get();

            $count = 0;
            foreach ($expiredAttempts as $attempt) {
                if ($attempt->order_id) {
                    // If there's an associated order, handle it as a payment failure
                    $this->handlePaymentFailure($attempt->order_id);
                } else {
                    // If no order is associated, just mark the attempt as completed
                    $attempt->update(['is_completed' => true]);
                }
                $count++;
            }

            Log::info("Processed {$count} expired payment attempts");
            return $count;
        } catch (\Exception $e) {
            Log::error('Error handling expired payment attempts: ' . $e->getMessage());
            return 0;
        }
    }

    public function cleanupExpiredPaymentAttempts()
    {
        $this->handleExpiredPaymentAttempts();
        return "Cleanup completed";
    }
}
