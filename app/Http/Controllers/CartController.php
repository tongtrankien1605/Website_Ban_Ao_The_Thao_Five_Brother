<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\Skus;
use App\Models\Variant;
use App\Models\Voucher;
use App\Models\VoucherUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function addToCart(Request $request, $id)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return response()->json(['message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng'], 401);
        }
    
        // Validate request
        $request->validate([
            'quantity' => 'required|integer|min:1',
            // 'variant_ids' => 'required|array',
            // 'variant_ids.*' => 'integer|exists:variants,id',
        ]);
    
        // Chuyển variant_ids thành mảng nếu cần
        $variantIds = is_array($request->variant_ids) ? $request->variant_ids : [$request->variant_ids];
    
        // Lấy hoặc tạo giỏ hàng của user
        $cart = Cart::firstOrCreate(['id_user' => Auth::id()]);
    
        // Tìm SKU phù hợp với các biến thể
        $data = Variant::select('id_skus')
            ->where('product_id', $id)
            ->whereIn('product_attribute_value_id', $variantIds)
            ->groupBy('id_skus')
            ->havingRaw('COUNT(DISTINCT product_attribute_value_id) = ?', [count($variantIds)])
            ->first();
    
        // Nếu không tìm thấy SKU phù hợp
        if (!$data) {
            return response()->json(['message' => 'Không tìm thấy biến thể nào'], 404);
        }
    
        // Lấy thông tin sản phẩm từ bảng Skus
        $productVariant = Skus::where('id', $data->id_skus)->first();

        $inventory = Inventory::where('id', $productVariant->id)->first();
        // dd($inventory);
    
        if (!$productVariant) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }
    
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItem = CartItem::where('id_cart', $cart->id)
            ->where('id_product_variant', $productVariant->id)
            ->first();
    
        if ($cartItem) {
            // Nếu sản phẩm đã có, tăng số lượng
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Nếu chưa có, tạo mới
            CartItem::create([
                'id_cart' => $cart->id,
                'id_product_variant' => $productVariant->id,
                'id_user' => Auth::id(),
                'quantity' => $request->quantity,
                'price' => $productVariant->sale_price ?? 0,
            ]);
            $inventory->update([
                'quantity' => $inventory->quantity - $request->quantity
            ]);
            $inventory->save();
            $inventory_log = InventoryLog::where('id', $inventory->id)->first();
            // dd($inventory_log);
            if ($inventory_log!=null) {
                $inventory_log->update([
                'old_quantity' => $inventory->quantity,
                'change_quantity' => $request->quantity,
                'new_quantity' => $inventory->quantity - $request->quantity,
                'reason' => 'Đã thêm vào giỏ hàng'
                ]);
                $inventory_log->save();
            } else {
                return response()->json(['message' => 'Không tìm thấy inventory_log'], 404);
            }
        }
    
        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng']);
    }

    public function applyVoucher(Request $request)
{
    $subtotal = CartItem::where('id_user', Auth::id())->sum(DB::raw('price * quantity'));
    $discountValue = $request->discount_value;
    $discountType = $request->discount_type;
    $newTotal = $subtotal;

    if ($discountType == 'percentage') {
        $newTotal = $subtotal - ($subtotal * $discountValue / 100);
    } elseif ($discountType == 'fixed') {
        $newTotal = $subtotal - $discountValue;
    }

    

    return response()->json([
        'success' => true,
        'new_total' => max($newTotal, 0) // Đảm bảo total không bị âm
    ]);
}

    
    public function index()
    {
        $cartItem = CartItem::where('id_user', Auth::id())->with('skuses')->get();
        $listVoucher = VoucherUser::where('id_user', Auth::id())->with('vouchers')->get();
        
        // dd($listVoucher->toArray());
        // echo '<pre>';
        // print_r($cartItem);
        // dd($cartItem->toArray());
        return view('client.cart', compact('cartItem', 'listVoucher'));
    }

    public function updateQuantity(Request $request, $id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        $total = CartItem::where('id_user', Auth::id())->sum(DB::raw('price * quantity'));

        return response()->json([
            'message' => 'Cập nhật giỏ hàng thành công',
            'subtotal' => $cartItem->quantity * $cartItem->price,
            'total' => $total
        ]);
    }

    public function remove($id) {
        // dd(1);
        $cartItem = CartItem::find($id);
    
        if (isset($cartItem)) {
            $cartItem->delete();
            return response()->json(['message' => 'Xóa thành công'], 200);
        }
    
        return response()->json(['error' => 'Xóa thất bại'], 404);
    }
}
