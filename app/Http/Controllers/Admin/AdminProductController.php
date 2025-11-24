<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
class AdminProductController extends Controller
{
      public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }
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
        return view('admin.products.create');
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
        return view('admin.products.edit', compact('product'));
    }

    // update
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('admin.products.index')->with('success','Updated');
    }

    // soft delete
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success','Deleted');
    }

}
