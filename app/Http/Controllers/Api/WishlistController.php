<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlist = Wishlist::with('product')
            ->where('user_id', $request->user()->id)
            ->when($request->product_id, function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Wishlist fetched successfully.',
            'data' => $wishlist,
        ], 200);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
        ]);

        $productId = $request->product_id;
        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'status' => true,
                'is_wishlisted' => false,
                'message' => 'Product removed from wishlist.',
            ], 200);
        }

        Wishlist::create([
            'user_id' => $request->user()->id,
            'product_id' => $productId,
        ]);

        return response()->json([
            'status' => true,
            'is_wishlisted' => true,
            'message' => 'Product added to wishlist.',
        ], 201);
    }

    public function destroy(Request $request, $wishlistId)
    {
        $wishlist = Wishlist::where('id', $wishlistId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'status' => false,
                'message' => 'Wishlist item not found.',
            ], 404);
        }

        $wishlist->delete();
        return response()->json([
            'status' => true,
            'message' => 'Wishlist item deleted successfully.',
        ], 200);
    }

}