<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('wishlist', compact('wishlists'));
    }

    public function toggle(Request $request, $productId)
    {
        $userId = Auth::id();
        
        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
            
        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            $status = 'added';
        }
        
        $wishlistCount = Wishlist::where('user_id', $userId)->count();
        
        return response()->json([
            'success' => true,
            'status' => $status,
            'wishlistCount' => $wishlistCount
        ]);

    }

    public function moveToCart(Request $request)
    {
        $userId = Auth::id();
        $wishlist = Wishlist::where('id', $request->wishlist_id)
            ->where('user_id', $userId)
            ->firstOrFail();
        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $wishlist->product_id)
            ->first();

        if ($cart) {
            $cart->quantity = $cart->quantity + 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $wishlist->product_id,
                'quantity' => 1,
            ]);
        }
        $wishlist->delete();
        return back()->with('success', 'Product moved to cart successfully.');
    }
    
}
