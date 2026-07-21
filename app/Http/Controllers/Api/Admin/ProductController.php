<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('price', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('c_id')) {
            $query->where('c_id', $request->c_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully.',
            'products' => $products,
        ], 200);
    }

    public function store(Request $request)
    {
        if ($request->has('products')) {
            return $this->storeMultiple($request);
        }

        return $this->storeSingle($request);
    }

    private function storeSingle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'c_id' => 'required|exists:category,id',
            'name' => 'required|string|max:255|unique:product,name',
            'slug' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        if (product::where('slug', $slug)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'The slug has already been taken.',
                'errors' => [
                    'slug' => ['The slug has already been taken.'],
                ],
            ], 422);
        }

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . Str::random(10) . '.' .
                $request->file('image')->getClientOriginalExtension();

            $request->file('image')->move(
                public_path('product'),
                $imageName
            );
        }

        $product = product::create([
            'c_id' => $request->c_id,
            'name' => $request->name,
            'slug' => $slug,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imageName,
            'status' => $request->status,
        ]);

        $product->load('category');

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully.',
            'product' => $product,
        ], 201);
    }

    private function storeMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.c_id' => 'required|exists:category,id',
            'products.*.name' => 'required|string|max:255|distinct|unique:product,name',
            'products.*.slug' => 'nullable|string|max:255|distinct',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.description' => 'nullable|string',
            'products.*.image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'products.*.status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $generatedSlugs = [];

        foreach ($request->products as $index => $item) {
            $slug = !empty($item['slug'])
                ? Str::slug($item['slug'])
                : Str::slug($item['name']);

            if (in_array($slug, $generatedSlugs)) {
                return response()->json([
                    'status' => false,
                    'message' => "Duplicate slug found at products.{$index}.slug.",
                    'errors' => [
                        "products.{$index}.slug" => [
                            'Duplicate slug found in request.',
                        ],
                    ],
                ], 422);
            }

            if (product::where('slug', $slug)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => "The slug {$slug} has already been taken.",
                    'errors' => [
                        "products.{$index}.slug" => [
                            "The slug {$slug} has already been taken.",
                        ],
                    ],
                ], 422);
            }

            $generatedSlugs[] = $slug;
        }

        $uploadedImages = [];

        try {
            $products = DB::transaction(function () use (
                $request,
                $generatedSlugs,
                &$uploadedImages
            ) {
                $createdProducts = [];

                foreach ($request->products as $index => $item) {
                    $imageName = null;

                    if (
                        isset($item['image']) &&
                        $item['image'] instanceof \Illuminate\Http\UploadedFile
                    ) {
                        $imageName = time() . '_' . $index . '_' .
                            Str::random(10) . '.' .
                            $item['image']->getClientOriginalExtension();

                        $item['image']->move(
                            public_path('product'),
                            $imageName
                        );

                        $uploadedImages[] = $imageName;
                    }

                    $createdProducts[] = product::create([
                        'c_id' => $item['c_id'],
                        'name' => $item['name'],
                        'slug' => $generatedSlugs[$index],
                        'price' => $item['price'],
                        'description' => $item['description'] ?? null,
                        'image' => $imageName,
                        'status' => $item['status'],
                    ]);
                }

                return $createdProducts;
            });
        } catch (\Throwable $e) {
            foreach ($uploadedImages as $image) {
                $path = public_path('product/' . $image);

                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Products could not be created.',
                'error' => $e->getMessage(),
            ], 500);
        }

        foreach ($products as $product) {
            $product->load('category');
        }

        return response()->json([
            'status' => true,
            'message' => 'Products created successfully.',
            'products' => $products,
        ], 201);
    }

    public function show($id)
    {
        $product = product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product fetched successfully.',
            'product' => $product,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $product = product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'c_id' => 'required|exists:category,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product', 'name')->ignore($product->id),
            ],
            'slug' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        $slugExists = product::where('slug', $slug)
            ->where('id', '!=', $product->id)
            ->exists();

        if ($slugExists) {
            return response()->json([
                'status' => false,
                'message' => 'The slug has already been taken.',
                'errors' => [
                    'slug' => ['The slug has already been taken.'],
                ],
            ], 422);
        }

        $oldImage = $product->image;
        $imageName = $oldImage;

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . Str::random(10) . '.' .
                $request->file('image')->getClientOriginalExtension();

            $request->file('image')->move(
                public_path('product'),
                $imageName
            );
        }

        try {
            $product->update([
                'c_id' => $request->c_id,
                'name' => $request->name,
                'slug' => $slug,
                'price' => $request->price,
                'description' => $request->description,
                'image' => $imageName,
                'status' => $request->status,
            ]);

            if ($request->hasFile('image') && $oldImage) {
                $oldImagePath = public_path('product/' . $oldImage);

                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
        } catch (\Throwable $e) {
            if ($request->hasFile('image')) {
                $newImagePath = public_path('product/' . $imageName);

                if (File::exists($newImagePath)) {
                    File::delete($newImagePath);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Product could not be updated.',
                'error' => $e->getMessage(),
            ], 500);
        }

        $product->load('category');

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully.',
            'product' => $product,
        ], 200);
    }

    public function changeStatus($id)
    {
        $product = product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $product->status = $product->status == 1 ? 0 : 1;
        $product->save();

        return response()->json([
            'status' => true,
            'message' => 'Product status updated successfully.',
            'product' => $product,
        ], 200);
    }

    public function destroy($id)
    {
        $product = product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        if ($product->image) {
            $imagePath = public_path('product/' . $product->image);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully.',
        ], 200);
    }
}