<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Rating;

class HomeController extends Controller
{

    public function getCategory()
    {
        $categories = Category::all();

        return response()->json([
            'data' => $categories
        ]);
    }

    public function getProducts()
    {
        $products = Product::with('category')->available()->get();

        return ProductResource::collection($products);
    }

    public function getProductById($id)
    {
 
        $product = Product::with('category','ratings')->available()->findOrFail($id);

        return response()->json([
            'product' => new ProductResource($product),
            'average_rating' => round($product->averageRating(), 1),
            'ratings' => $product->ratings
]);

    }

 public function filterProducts(ProductRequest $request)
{
    $products = Product::with('category')
        ->available()
        ->when($request->filled('category_id'), function ($query) use ($request) {
            $query->where('category_id', $request->category_id);
        })
        ->when($request->filled('is_featured'), function ($query) use ($request) {
            $query->where('is_featured', $request->is_featured);
        })
        ->when($request->filled('min_price'), function ($query) use ($request) {
            $query->where('price', '>=', $request->min_price);
        })
        ->when($request->filled('max_price'), function ($query) use ($request) {
            $query->where('price', '<=', $request->max_price);
        })
        ->get();

    return ProductResource::collection($products);
}
//add rating
public function addProductRating(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string',
    ]);

    $rating = Rating::create([
        'product_id' => $request->product_id,
        'user_id' => $request->user()->id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return response()->json([
        'message' => 'Rating added successfully',
        'data' => $rating
    ]);
}




}
