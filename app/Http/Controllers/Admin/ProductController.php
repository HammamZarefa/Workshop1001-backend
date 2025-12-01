<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StockUpdateRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Traits\MediaUploadTrait;
use App\Http\Traits\MediaDeletionTrait;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    use MediaUploadTrait ,MediaDeletionTrait;
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
        $product = Product::create($data);

        $this->uploadSingleMedia($product, $request, 'featured','featured');

        $this->uploadMultipleMedia($product, $request, 'gallery','gallery');

        return redirect()->route('admin.products.index')->with('success', 'Created');
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

        $this->uploadSingleMedia($product, $request, 'featured', 'featured');

        $this->uploadMultipleMedia($product, $request, 'gallery', 'gallery');

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
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

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'The product was successfully deleted');
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

    return redirect()
        ->route('admin.products.index')
        ->with('success', 'Stock updated successfully');
}
    public function destroyGallery($media)
    {
        $result = $this->deleteMediaById($media);

        return response()->json($result, $result['success'] ? 200 : 500);
    }











}
