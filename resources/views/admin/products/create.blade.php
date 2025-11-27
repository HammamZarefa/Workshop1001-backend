@extends('layouts.main')

@section('title', 'Create Product')

@section('content')

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-3">Create Product</h1>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="leading-loose p-10 bg-white rounded shadow-xl space-y-6">

                <!-- Title -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Product Title</label>
                    <input class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transitiond"
                           name="title" type="text" required>
                </div>

                <!-- Description -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Description</label>
                    <textarea class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                              name="description" rows="4"></textarea>
                </div>
                <!-- Category -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Category</label>
                    <select name="category_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Price -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Price</label>
                    <input class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                           name="price" type="number" step="0.01">
                </div>

                <!-- Stock -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Stock</label>
                    <input class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                           name="stock" type="number">
                </div>

                <!-- Featured Image -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Featured Image</label>
                    <input type="file" name="featured" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                </div>

                <!-- Gallery -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Gallery Images</label>
                    <input type="file" name="gallery[]" multiple class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                </div>

            </div>

            <button type="submit"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg">
                Save Product
            </button>


        </form>
    </div>

@endsection
