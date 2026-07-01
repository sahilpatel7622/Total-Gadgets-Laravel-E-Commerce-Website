<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\category;

class ProductController extends Controller
{
    public function products(Request $request)
    {
        $query = product::with('category')
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            });

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('category', function ($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('category')) {
            $category = category::where('slug', $request->category)
                ->where('status', 1)
                ->first();

            if ($category) {
                $query->where('c_id', $category->id);
            }
        }

        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            default:
                $query->latest();
                break;
        }

        $product = $query->get();

        $categories = category::where('status', 1)
            ->orderBy('name')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('product_ajax', compact('product'))->render(),
                'count' => $product->count()
            ]);
        }

        return view('products', compact('product', 'categories'));
    }

    public function productDetail($slug)
    {
        $product = product::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = product::with('category')
            ->where('c_id', $product->c_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('product_detail', compact('product', 'relatedProducts'));
    }
}