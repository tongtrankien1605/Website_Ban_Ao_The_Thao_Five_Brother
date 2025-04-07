<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Inventory;
use App\Models\InventoryEntry;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\Skus;
use App\Models\Variant;
use App\Models\Voucher;
use App\Models\VoucherUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

  public function addToCart(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'variant_ids' => 'required|array',
        'variant_ids.*' => 'exists:product_atribute_values,id',
    ]);

    DB::beginTransaction();

    $variantIds = $request->variant_ids;
    // dd($variantIds);

    $cart = Cart::firstOrCreate(['id_user' => Auth::id()]);

    $variant = Variant::select('id_skus')
        ->where('product_id', $id)
        ->whereIn('product_attribute_value_id', $variantIds)
        ->groupBy('id_skus')
        ->havingRaw('COUNT(DISTINCT product_attribute_value_id) = ?', [count($variantIds)])
        ->first();
    $productVariant = Skus::find($variant->id_skus);

    $inventorylog = InventoryEntry::where('id_skus', $productVariant->id)->first();

    // dd($variant);
    if (!$variant) {
        return response()->json(['message' => 'Không tìm thấy biến thể nào'], 404);
    }

    $productVariant = Skus::find($variant->id_skus);

    if (!$productVariant) {
        return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
    }

    $inventorylog = InventoryEntry::where('id_skus', $productVariant->id)->first();
    $inventory = Inventory::where('id', $productVariant->id)->first();


    if (!$inventory || $inventory->quantity < 1) {
        return response()->json(['message' => 'Sản phẩm đã hết hàng'], 400);
    }

    $cartItem = CartItem::where('id_cart', $cart->id)
        ->where('id_product_variant', $productVariant->id)
        ->first();

    $requestedQty = $request->quantity;
    $existingQty = $cartItem ? $cartItem->quantity : 0;
    $totalQty = $requestedQty + $existingQty;

    if ($totalQty > $inventory->quantity) {
        return response()->json([
            'message' => '❌ Số lượng bạn đặt vượt quá tồn kho. Chỉ còn lại ' . $inventory->quantity . ' sản phẩm.'
        ], 400);
    }

    $price = (!empty($inventorylog->discount_end) 
        && $inventorylog->discount_end !== '0000-00-00 00:00:00' 
        && Carbon::now()->lessThan(Carbon::parse($inventorylog->discount_end)))
        ? $inventorylog->sale_price
        : $inventorylog->price;

    if ($cartItem) {
        $cartItem->quantity += $requestedQty;
        $cartItem->save();
    } else {
        CartItem::create([
            'id_cart' => $cart->id,
            'id_product_variant' => $productVariant->id,
            'id_user' => Auth::id(),
            'quantity' => $requestedQty,
            'price' => $price,
        ]);
    }

    DB::commit();
    return response()->json(['message' => '✅ Sản phẩm đã được thêm vào giỏ hàng']);
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
        // dd($cartItem->toArray());
        $listVoucher = VoucherUser::where('id_user', Auth::id())->with('vouchers')->get();
        $inventory = Inventory::whereIn('id', collect($cartItem)->pluck('id_product_variant'))->get();
        // dd($inventory->toArray());
        // dd($listVoucher->toArray());
        // echo '<pre>';
        // print_r($cartItem)
        // dd($cartItem->toArray());
        return view('client.cart', compact('cartItem', 'listVoucher','inventory'));
    }

    public function updateQuantity(Request $request, $id)
    {
        $cartItem = CartItem::find($id);
    
        if (!$cartItem) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }
    
        // Lấy thông tin tồn kho
        $inventory = InventoryEntry::where('id_skus', $cartItem->id_product_variant)->first();
    
        if (!$inventory) {
            return response()->json(['message' => 'Không tìm thấy thông tin kho hàng'], 404);
        }
    
        if ($request->quantity < 1) {
            return response()->json(['message' => 'Số lượng phải lớn hơn 0'], 400);
        }
    
        if ($request->quantity > $inventory->quantity) {
            return response()->json([
                'message' => '❌ Số lượng vượt quá tồn kho. Chỉ còn lại ' . $inventory->quantity . ' sản phẩm.'
            ], 400);
        }
    
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
    
        $total = CartItem::where('id_user', Auth::id())
            ->sum(DB::raw('price * quantity'));
    
        return response()->json([
            'message' => '✅ Cập nhật giỏ hàng thành công',
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
