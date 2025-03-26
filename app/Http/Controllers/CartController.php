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
        if (!Auth::check()) {
            return response()->json(['message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng'], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variant_ids' => 'required|array',
            'variant_ids.*' => 'exists:product_atribute_values,id',
        ]);
        DB::beginTransaction();
        $variantIds = is_array($request->variant_ids) ? $request->variant_ids : [$request->variant_ids];

        $cart = Cart::firstOrCreate(['id_user' => Auth::id()]);

        $data = Variant::select('id_skus')
            ->where('product_id', $id)
            ->whereIn('product_attribute_value_id', $variantIds)
            ->groupBy('id_skus')
            ->havingRaw('COUNT(DISTINCT product_attribute_value_id) = ?', [count($variantIds)])
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Không tìm thấy biến thể nào'], 404);
        }

        $productVariant = Skus::where('id', $data->id_skus)->first();

        if (!$productVariant) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }
        $inventory = Inventory::where('id_product_variant', $productVariant->id)->first();
        $oldQuantity = $inventory->quantity;
        if ($oldQuantity < 1) {
            return response()->json(['message' => 'Sản phẩm đã hết hàng, vui lòng chọn sản phẩm khác.'], 500);
        }
        $cartItem = CartItem::where('id_cart', $cart->id)
            ->where('id_product_variant', $productVariant->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();

            $inventory->quantity = $oldQuantity - $request->quantity;
            if (!$inventory->save()) {
                DB::rollBack();
                return response()->json(['message' => 'Đã xảy ra lỗi'], 500);
            }
            InventoryLog::create([
                'id_product_variant' => $productVariant->id,
                'user_id' => auth()->user()->id,
                'old_quantity' => $oldQuantity,
                'change_quantity' => $request->quantity,
                'new_quantity' => $inventory->quantity,
                'reason' => 'Đã thêm vào giỏ hàng'
            ]);
        } else {
            CartItem::create([
                'id_cart' => $cart->id,
                'id_product_variant' => $productVariant->id,
                'id_user' => Auth::id(),
                'quantity' => $request->quantity,
                'price' => $productVariant->sale_price,
            ]);
            $inventory->quantity = $oldQuantity - $request->quantity;
            if (!$inventory->save()) {
                DB::rollBack();
                return response()->json(['message' => 'Đã xảy ra lỗi'], 500);
            }
            InventoryLog::create([
                'id_product_variant' => $productVariant->id,
                'user_id' => auth()->user()->id,
                'old_quantity' => $oldQuantity,
                'change_quantity' => $request->quantity,
                'new_quantity' => $inventory->quantity,
                'reason' => 'Đã thêm vào giỏ hàng'
            ]);
        }
        DB::commit();
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

    public function remove($id)
    {
        // dd(1);
        $cartItem = CartItem::find($id);

        if (isset($cartItem)) {
            $cartItem->delete();
            return response()->json(['message' => 'Xóa thành công'], 200);
        }

        return response()->json(['error' => 'Xóa thất bại'], 404);
    }
}
