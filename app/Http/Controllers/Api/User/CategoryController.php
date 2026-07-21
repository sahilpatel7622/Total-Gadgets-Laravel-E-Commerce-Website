<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = category::with(['product' => function ($query) {
            $query->select('id', 'c_id', 'name', 'slug', 'price', 'image', 'description');
        }])
        ->where('status', 1)
        ->latest()
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Category with products fetched successfully',
            'data' => $categories
        ]);
    }

    public function show($id)
    {
        $category = category::with('product')
            ->where('status', 1)
            ->find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Category with products fetched successfully',
            'data' => $category
        ]);
    }
}