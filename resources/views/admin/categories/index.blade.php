@extends('layouts.main')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">All Categories</h1>
        <a href="{{ route('admin.categories.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
            إضافة تصنيف جديد
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <ul>
        @foreach ($categories as $category)
            <li class="mb-3">
                @if ($category->getFirstMediaUrl('categories'))
                    <div class="w-20 h-20 overflow-hidden rounded border mb-2">
                        <img src="{{ $category->getFirstMediaUrl('categories') }}"
                             class="w-full h-full object-cover">
                    </div>
                @else
                    <span class="text-gray-500">لا توجد صورة</span>
                @endif

                <div class="font-semibold">{{ $category->title }}</div>

                <div class="flex gap-3 mt-1">
                    <a class="text-blue-600" href="{{ route('admin.categories.show', $category->id) }}">عرض</a>
                    <a class="text-yellow-600" href="{{ route('admin.categories.edit', $category->id) }}">تعديل</a>

                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600">حذف</button>
                    </form>
                </div>

                @if ($category->children->count())
                    <ul class="ml-6">
                        @include('admin.categories.partials.tree', ['children' => $category->children])
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>

</div>
@endsection
