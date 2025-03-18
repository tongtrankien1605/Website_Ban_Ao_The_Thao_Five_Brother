<?php
namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')->where('id_user', Auth::id())->get();
        // dd($wishlists->toArray());
        return view('client.wishlist',compact('wishlists'));
    }

    public function store($id)
    {
        // dd(vars: Auth::id());
        $wishlist = Wishlist::firstOrCreate([
            'id_user' => Auth::id(),
            'id_product' => $id,
        ]);

        return response()->json(['message' => 'Sản phẩm đã thêm vào wishlist']);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('id_user', Auth::id())->where('id_product', $id)->first();
        if ($wishlist) {
            $wishlist->delete();
            return redirect()->route('index_wishlist');
        }

        return response()->json(['message' => 'Sản phẩm không tồn tại trong wishlist'], 404);
    }
}
