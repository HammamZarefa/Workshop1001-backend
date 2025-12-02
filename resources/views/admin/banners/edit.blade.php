@extends('layouts.main')

@section('content')

<h2 class="text-3xl font-bold text-gray-800 mb-6">Edit Banner</h2>

<div class="bg-white shadow rounded-xl p-6">

    <form action="{{ route('admin.banners.update', $banner->id) }}"
          method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <table class="w-full border-separate border-spacing-y-4">

            <tr>
                <td class="w-1/4 text-gray-700 font-medium">Title</td>
                <td>
                    <input type="text" name="title"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2"
                           value="{{ $banner->title }}">
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Description</td>
                <td>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ $banner->description }}</textarea>
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Link</td>
                <td>
                    <input type="text" name="link"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2"
                           value="{{ $banner->link }}">
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Sort Order</td>
                <td>
                    <input type="number" name="sort_order"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2"
                           value="{{ $banner->sort_order }}">
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Current Image</td>
                <td>
                    @if ($banner->image_url)
                        <img src="{{ $banner->image_url }}" class="w-32 h-20 rounded border mb-3">
                    @else
                        <span class="text-gray-400 italic">No Image</span>
                    @endif
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Replace Image</td>
                <td>
                    <input type="file" name="image"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Active</td>
                <td>
                    <input type="checkbox" name="is_active" value="1"
                           class="w-5 h-5"
                           {{ $banner->is_active ? 'checked' : '' }}>
                </td>
            </tr>

        </table>

        <div class="mt-6">
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Update Banner
            </button>
        </div>

    </form>

</div>

@endsection
