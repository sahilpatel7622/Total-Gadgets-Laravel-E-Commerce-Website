<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:category,id',
            'name' => 'required|unique:product,name',
            'slug' => 'required|unique:product,slug',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('product'), $imageName);
        }

        $product = product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'image' => $imageName,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product Created Successfully',
            'data' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }

    public function update(Request $request, string $id)
    {
        $product = product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:category,id',
            'name' => 'required|unique:products,name,' . $id,
            'slug' => 'required|unique:products,slug,' . $id,
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('product/' . $product->image))) {
                unlink(public_path('product/' . $product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('product'), $imageName);

            $product->image = $imageName;
        }

        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();

        return response()->json([
            'status' => true,
            'message' => 'Product Updated Successfully',
            'data' => $product
        ]);
    }

    public function destroy(string $id)
    {
        $product = product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ], 404);
        }

        if ($product->image && file_exists(public_path('product/' . $product->image))) {
            unlink(public_path('product/' . $product->image));
        }

        $product->delete();
        return response()->json([
            'status' => true,
            'message' => 'Product Deleted Successfully'
        ]);
    }
}