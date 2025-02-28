<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Skus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function addToCart($id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng'], 401);
        }

        // Tìm hoặc tạo giỏ hàng
        $cart = Cart::firstOrCreate(['id_user' => Auth::id()]);

        // Lấy biến thể sản phẩm (chỉ lấy một bản ghi)
        $productVariant = Skus::where('product_id', $id)->first();

        if (!$productVariant) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $cartItem = CartItem::where('id_cart', $cart->id)
            ->where('id_product_variant', $productVariant->id)
            ->first();
        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            CartItem::create([
                'id_cart' => $cart->id,
                'id_product_variant' => $productVariant->id,
                'id_user' => Auth::id(),
                'quantity' => 1,
                'price' => $productVariant->price,
            ]);
        }

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng']);
    }


    public function index()
    {
        $cartItem = CartItem::where('id_user', Auth::id())->with('skuses')->get();
        // echo '<pre>';
        // print_r($cartItem);
        // dd($cartItem->toArray());
        return view('client.cart', compact('cartItem'));
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
