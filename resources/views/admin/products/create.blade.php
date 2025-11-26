@extends('layouts.main')

@section('title', 'Create Product')

@section('content')

<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-3">Create Product</h1>
    
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Name -->
    <div>
        <label class="block mb-2 font-semibold text-gray-700">Product Name</label>
        <input type="text" name="name" value="{{ old('name') }}" 
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
                <option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Prices -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-2 font-semibold text-gray-700">Min Price</label>
            <input type="number" name="min_price" value="{{ old('min_price') }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
        </div>
        <div>
            <label class="block mb-2 font-semibold text-gray-700">Max Price</label>
            <input type="number" name="max_price" value="{{ old('max_price') }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
        </div>
    </div>

    <!-- Featured Image -->
    <div>
        <label class="block mb-2 font-semibold text-gray-700">Featured Image</label>
        <input type="file" name="featured" class="w-full text-gray-700">
    </div>

    <!-- Gallery Images -->
    <div>
        <label class="block mb-2 font-semibold text-gray-700">Gallery Images</label>
        <input type="file" name="gallery[]" multiple class="w-full text-gray-700">
    </div>

    <!-- Submit Button -->
    <div class="pt-4">
        <button type="submit" 
                class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition">
            Save Product
        </button>
    </div>
</form>


</div>
@endsection
