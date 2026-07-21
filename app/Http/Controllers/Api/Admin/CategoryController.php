<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = category::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully.',
            'categories' => $categories,
        ], 200);
    }

    public function store(Request $request)
    {
        if ($request->has('categories')) {
            return $this->storeMultiple($request);
        }

        return $this->storeSingle($request);
    }

    private function storeSingle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:category,name',
            'slug' => 'nullable|string|max:255',
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

        if (category::where('slug', $slug)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'The slug has already been taken.',
                'errors' => [
                    'slug' => [
                        'The slug has already been taken.',
                    ],
                ],
            ], 422);
        }

        $category = category::create([
            'name' => $request->name,
            'slug' => $slug,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully.',
            'category' => $category,
        ], 201);
    }

    private function storeMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categories' => 'required|array|min:1',
            'categories.*.name' => 'required|string|max:255|distinct|unique:category,name',
            'categories.*.slug' => 'nullable|string|max:255|distinct',
            'categories.*.status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $generatedSlugs = [];

        foreach ($request->categories as $index => $item) {
            $slug = !empty($item['slug'])
                ? Str::slug($item['slug'])
                : Str::slug($item['name']);

            if (in_array($slug, $generatedSlugs)) {
                return response()->json([
                    'status' => false,
                    'message' => "Duplicate slug found at categories.{$index}.slug.",
                    'errors' => [
                        "categories.{$index}.slug" => [
                            'Duplicate slug found in request.',
                        ],
                    ],
                ], 422);
            }

            if (category::where('slug', $slug)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => "The slug {$slug} has already been taken.",
                    'errors' => [
                        "categories.{$index}.slug" => [
                            "The slug {$slug} has already been taken.",
                        ],
                    ],
                ], 422);
            }

            $generatedSlugs[] = $slug;
        }

        $categories = DB::transaction(function () use ($request, $generatedSlugs) {
            $createdCategories = [];

            foreach ($request->categories as $index => $item) {
                $createdCategories[] = category::create([
                    'name' => $item['name'],
                    'slug' => $generatedSlugs[$index],
                    'status' => $item['status'],
                ]);
            }

            return $createdCategories;
        });

        return response()->json([
            'status' => true,
            'message' => 'Categories created successfully.',
            'categories' => $categories,
        ], 201);
    }

    public function show($id)
    {
        $category = category::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Category fetched successfully.',
            'category' => $category,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $category = category::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('category', 'name')->ignore($category->id),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],
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

        $slugExists = category::where('slug', $slug)
            ->where('id', '!=', $category->id)
            ->exists();

        if ($slugExists) {
            return response()->json([
                'status' => false,
                'message' => 'The slug has already been taken.',
                'errors' => [
                    'slug' => [
                        'The slug has already been taken.',
                    ],
                ],
            ], 422);
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully.',
            'category' => $category,
        ], 200);
    }

    public function changeStatus($id)
    {
        $category = category::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found.',
            ], 404);
        }

        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category status updated successfully.',
            'category' => $category,
        ], 200);
    }

    public function destroy($id)
    {
        $category = category::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found.',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully.',
        ], 200);
    }
}