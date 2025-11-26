<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StockUpdateRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;

class AdminProductController extends Controller
{
     
    /**
     * Display a listing of the resource.
     */
  
    // index
    public function index()
    {
        $products = Product::orderBy('created_at','desc')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    // create 
    public function create()
    {
    $categories = Category::all(); 
    return view('admin.products.create', compact('categories'));
    }


    //store
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success','Created');
    }


    // edit
   public function edit(Product $product)
{
    $categories = Category::all(); 
    return view('admin.products.edit', compact('product', 'categories'));
}

    // update
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('admin.products.index')->with('success','Updated');
    }

    // soft delete
    public function destroy($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'product not found'
        ], 404);
    }

    
    if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
        unlink(public_path('uploads/products/' . $product->image));
    }

    
    if ($product->gallery) {
        foreach ($product->gallery as $img) {
            if (file_exists(public_path('uploads/products/gallery/' . $img))) {
                unlink(public_path('uploads/products/gallery/' . $img));
            }
        }
    }


    $product->delete();

    return response()->json([
        'success' => true,
        'message' => 'The Product Was Successfully Deleted'
    ], 200);
}
    //update Stock
   public function updateStock(StockUpdateRequest $request, $id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'product not found'
        ], 404);
    }

    $data = $request->validated();

    if (isset($data['delta'])) {
        $product->stock += $data['delta'];
    }

    if (isset($data['stock'])) {
        $product->stock = $data['stock'];
    }

    if ($product->stock < 0) {
        $product->stock = 0;
    }

    $product->save();

    return response()->json([
        'success' => true,
        'message' => 'product updated successfully',
        'stock' => $product->stock
    ], 200);
}
   



}
