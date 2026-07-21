<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlist = Wishlist::with('product.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Wishlist fetched successfully',
            'data' => $wishlist
        ], 200);
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,id',
        ]);

        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'status' => true,
                'message' => 'Product removed from wishlist'
            ], 200);
        }

        $wishlist = Wishlist::create([
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product added to wishlist',
            'data' => $wishlist->load('product.category')
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->find($id);

        if (!$wishlist) {
            return response()->json([
                'status' => false,
                'message' => 'Wishlist item not found'
            ], 404);
        }

        $wishlist->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from wishlist'
        ], 200);
    }
}