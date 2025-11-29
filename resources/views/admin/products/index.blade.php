<!-- resources/views/admin/product/index.blade.php -->
@extends('layouts.main')

@section('title', 'Products List')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md inline-block"
    >Add Product</a>
</div>

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-200 text-left">
            <th class="px-4 py-2">photo</th>
            <th class="px-4 py-2">title</th>
            <th class="px-4 py-2">Category</th>
            <th class="px-4 py-2">Price</th>
            <th class="px-4 py-2">Stock</th>
            <th class="px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr class="border-b">
            <td class="px-4 py-2 text-center">
                @php
                    $featured = $product->getFirstMediaUrl('featured');
                @endphp

                @if($featured)
                    <img src="{{ $featured }}" alt="Featured Image" class="w-16 h-16 object-cover mx-auto rounded">
                @else
                    <span class="text-gray-400">No Image</span>
                @endif
            </td>



            <td class="px-4 py-2 text-center">{{ $product->title }}</td>
            <td class="px-4 py-2 text-center">{{ $product->category?->id }}</td>
            <td class="px-4 py-2 text-center">{{ $product->price}}</td>
            <td class="px-4 py-2 text-center">{{ $product->stock }}</td>

            <td class="px-4 py-2 space-x-2 text-center">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-3 py-1.5 rounded-md inline-block">Edit</a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-3 py-1.5 rounded-md inline-block" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
