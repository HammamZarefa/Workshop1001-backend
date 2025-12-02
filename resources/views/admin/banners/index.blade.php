@extends('layouts.main')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Banners List</h2>
    <a href="{{ route('admin.banners.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        + Add Banner
    </a>
</div>

<div class="bg-white shadow rounded-xl p-6">

    <table class="min-w-full table-fixed border-separate border-spacing-y-3">
        <thead>
            <tr class="text-gray-600 text-left">
                <th class="px-4 py-2">Image</th>
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Link</th>
                <th class="px-4 py-2">Sort</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2 text-right">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($banners as $banner)
                <tr class="bg-gray-50 rounded-lg shadow-sm">
                    <td class="px-4 py-3">
                        @if($banner->image_url)
                            <img src="{{ $banner->image_url }}" class="w-20 h-12 rounded object-cover">
                        @else
                            <span class="text-gray-400 italic">No Image</span>
                        @endif
                    </td>

                    <td class="px-4 py-3 font-medium">{{ $banner->title }}</td>

                    <td class="px-4 py-3 text-blue-600">
                        {{ $banner->link ?? '—' }}
                    </td>

                    <td class="px-4 py-3">{{ $banner->sort_order }}</td>

                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-sm
                            {{ $banner->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.banners.edit', $banner->id) }}"
                           class="text-yellow-600 hover:text-yellow-800 font-medium mr-4">
                            Edit
                        </a>

                        <form action="{{ route('admin.banners.destroy', $banner->id) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Delete this banner?')"
                                    class="text-red-600 hover:text-red-800 font-medium">
                                Delete
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection
