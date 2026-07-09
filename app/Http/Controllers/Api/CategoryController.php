<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = category::latest()->get();
        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }
    
    public function store(Request $req)
    {
        // Multiple Insert
        if ($req->has('categories')) {

            $validator = Validator::make($req->all(), [
                'categories' => 'required|array',
                'categories.*.name' => 'required|unique:category,name',
                'categories.*.slug' => 'required|unique:category,slug',
                'categories.*.status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()
                ], 422);
            }
            $data = [];
            foreach ($req->categories as $category) {
                $data[] = Category::create($category);
            }
            return response()->json([
                'status' => true,
                'message' => 'Multiple Categories Created Successfully',
                'data' => $data
            ], 201);
        }

        // Single Insert
        $validator = Validator::make($req->all(), [
            'name' => 'required|unique:category,name',
            'slug' => 'required|unique:category,slug',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ], 422);
        }

        $category = Category::create([
            'name' => $req->name,
            'slug' => $req->slug,
            'status' => $req->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category Created Successfully',
            'data' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Category::with('product')   
            ->where('id', $id)
            ->first();        

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category Not Found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $category
        ]);
    }

    public function update(Request $req, string $id)
    {
        $category = category::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category Not Found'
            ], 404);
        }

        $validator = Validator::make($req->all(), [
            'name'   => 'required|unique:category,name,' . $id,
            'slug'   => 'required|unique:category,slug,' . $id,
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ], 422);
        }

        $category->update([
            'name' => $req->name,
            'slug' => $req->slug,
            'status' => $req->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category Updated Successfully',
            'data' => $category
        ]);
    }

    public function destroy(string $id)
    {
        $category = category::find($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category Not Found'
            ], 404);
        }
        $category->delete();
        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully'
        ]);
    }



}
