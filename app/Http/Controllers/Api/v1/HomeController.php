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
        $categories = Category::all();

        return response()->json([
            'data' => $categories
        ]);
    }

    public function getProducts()
    {
        $products = Product::with('category')->paginate(15);

        return ProductResource::collection($products);
    }

    public function getProductById($id)
    {

        $product = Product::with('category')->findOrFail($id);

        return new ProductResource($product);
    }

 public function filterProducts(ProductRequest $request)
{
    $products = Product::with('category')
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
        ->paginate(15) ;

    return ProductResource::collection($products);
}



}
