<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $cart = Cart::where('user_id', $user_id)
            ->with('product')
            ->get();

        return response()->json([
            'status' => true,
            'cart' => $cart,
            'cart_count' => $cart->count(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $user_id = Auth::id();
        $cart = Cart::where('user_id', $user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $cart->quantity = $cart->quantity + $request->quantity;
            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => $user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully',
            'cart' => $cart,
        ]);
    }

    public function show(string $id)
    {
        $user_id = Auth::id();
        $cart = Cart::where('user_id', $user_id)
            ->with('product')
            ->find($id);

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'item' => $cart,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user_id = Auth::id();
        $cart = Cart::where('user_id', $user_id)->find($id);
        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully',
            'cart' => $cart,
        ]);
    }

    public function destroy(string $id)
    {
        $user_id = Auth::id();
        $cart = Cart::where('user_id', $user_id)->find($id);
        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $cart->delete();
        return response()->json([
            'status' => true,
            'message' => 'Product removed from cart',
        ]);
    }

}