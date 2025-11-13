<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;

class HomeController extends Controller
{
    
    public function getCategory()
    {
        $categories = Category::where('is_active', true)->get();

        return response()->json([
            'data' => $categories
        ]);
    }

  
    public function index()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->get();

        return ProductResource::collection($products);
    }

  
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return new ProductResource($product);
    }

   
    public function filter(ProductRequest $request)
    {
        $query = Product::query()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->where('is_active', true)->get();

        return ProductResource::collection($products);
    }
}
