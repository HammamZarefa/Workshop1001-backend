@extends('layouts.main')

@section('content')

<h2 class="text-3xl font-bold text-gray-800 mb-6">Create Banner</h2>

<div class="bg-white shadow rounded-xl p-6">

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <table class="w-full border-separate border-spacing-y-4">

            <tr>
                <td class="w-1/4 text-gray-700 font-medium">Title</td>
                <td>
                    <input type="text" name="title" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Description</td>
                <td>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Link</td>
                <td>
                    <input type="text" name="link"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Sort Order</td>
                <td>
                    <input type="number" name="sort_order" value="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Image</td>
                <td>
                    <input type="file" name="image"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </td>
            </tr>

            <tr>
                <td class="text-gray-700 font-medium">Active</td>
                <td>
                    <input type="checkbox" name="is_active" value="1" checked
                           class="w-5 h-5">
                </td>
            </tr>

        </table>

        <div class="mt-6">
            <button class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                Save Banner
            </button>
        </div>

    </form>

</div>

@endsection
