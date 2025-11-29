<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Models\Rating;
use App\Http\Traits\ApiResponses;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\RatingRequest;

class HomeController extends ApiController
{

    public function getCategory()
    {
        $categories = Category::orderBy('id', 'asc')->get();

        return CategoryResource::collection($categories);
    }

    public function getProducts()
    {
        return $this->tryCall(function () {

        $products = Product::with('category')->available()->paginate(15);

        return $this->success('Products loaded', ProductResource::collection($products));
        });
    }

    public function getProductById($id)
    {

        return $this->tryCall(function () use ($id) {
        $product = Product::with('category','ratings')->available()->findOrFail($id);

        return $this->ok('Product loaded', [
                'product'        => new ProductResource($product),
                'average_rating' => round($product->averageRating(), 1),
                'ratings'        => $product->ratings
        ]);
});
    }

 public function filterProducts(ProductRequest $request)
{
    return $this->tryCall(function () use ($request) {
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
        ->paginate(15);

     return $this->success('Filtered products', ProductResource::collection($products));
    });
}
//add rating
public function addProductRating(Request $request)
{
    return $this->tryCall(function () use ($request) {
    $rating = Rating::create([
        'product_id' => $request->product_id,
        'user_id' => $request->user()->id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return $this->ok('Rating added successfully', $rating);
    });

}

}