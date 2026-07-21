<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = product::with('category')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('c_id', $request->category_id);
            })
            ->when($request->sort == 'low_to_high', function ($query) {
                $query->orderBy('price', 'asc');
            })
            ->when($request->sort == 'high_to_low', function ($query) {
                $query->orderBy('price', 'desc');
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully',
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product fetched successfully',
            'data' => $product
        ]);
    }
}