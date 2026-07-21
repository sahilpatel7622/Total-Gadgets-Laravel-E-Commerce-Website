<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = Cart::with('product.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return response()->json([
            'status' => true,
            'message' => 'Cart fetched successfully',
            'data' => $cartItems,
            'cart_total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = product::find($validated['product_id']);

        $cart = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $cart->quantity += $validated['quantity'];
            $cart->save();
        } else {
            $cart = Cart::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully',
            'data' => $cart->load('product'),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $cart = Cart::with('product.category')
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Cart item fetched successfully',
            'data' => $cart,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', $request->user()->id)
            ->find($id);

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $cart->update([
            'quantity' => $validated['quantity'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cart quantity updated successfully',
            'data' => $cart->load('product'),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $cart = Cart::where('user_id', $request->user()->id)
            ->find($id);

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $cart->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from cart successfully',
        ]);
    }
}