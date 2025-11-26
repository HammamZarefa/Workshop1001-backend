@extends('layouts.main')

@section('title', 'Edit Product')

@section('content')

<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-3">Edit Product</h1>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

```
<!-- Name -->
<div>
    <label class="block mb-2 font-semibold text-gray-700">Product Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name) }}" 
           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
    @error('name')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Category -->
<div>
    <label class="block mb-2 font-semibold text-gray-700">Category</label>
    <select name="category_id" 
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
        <option value="">-- Select Category --</option>
        @foreach($categories as $category)
           <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                {{ $category->title }}
           </option>

        @endforeach
    </select>
</div>

<!-- Prices -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block mb-2 font-semibold text-gray-700">Min Price</label>
        <input type="number" name="min_price" value="{{ old('min_price', $product->min_price) }}" 
               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
    </div>
    <div>
        <label class="block mb-2 font-semibold text-gray-700">Max Price</label>
        <input type="number" name="max_price" value="{{ old('max_price', $product->max_price) }}" 
               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
    </div>
</div>

<!-- Featured Image -->
<div>
    <label class="block mb-2 font-semibold text-gray-700">Featured Image</label>
    @if($product->featured)
        <img src="{{ asset('storage/' . $product->featured) }}" alt="Featured Image" class="mb-2 w-32 h-32 object-cover rounded">
    @endif
    <input type="file" name="featured" class="w-full text-gray-700">
</div>

<!-- Gallery Images -->
<div>
    <label class="block mb-2 font-semibold text-gray-700">Gallery Images</label>
    @if($product->gallery)
        <div class="flex flex-wrap gap-2 mb-2">
            @foreach($product->gallery as $image)
                <img src="{{ asset('storage/' . $image) }}" class="w-20 h-20 object-cover rounded" alt="Gallery Image">
            @endforeach
        </div>
    @endif
    <input type="file" name="gallery[]" multiple class="w-full text-gray-700">
</div>

<!-- Submit Button -->
<div class="pt-4">
    <button type="submit" 
            class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition">
        Update Product
    </button>
</div>
```

</form>

</div>
@endsection
