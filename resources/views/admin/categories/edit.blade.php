@extends('layouts.main')
@section('content')

<div class="p-6 bg-white rounded shadow">

    <h2 class="text-2xl font-bold mb-6">تعديل التصنيف</h2>

    <form action="{{ route('admin.categories.update', $category->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <!-- عنوان التصنيف -->
        <div class="mb-4">
            <label class="block mb-2 font-semibold">عنوان التصنيف</label>
            <input type="text"
                   name="title"
                   value="{{ old('title', $category->title) }}"
                   class="border p-2 w-full rounded">
        </div>

        <!-- التصنيف الأب -->
        <div class="mb-4">
            <label class="block mb-2 font-semibold">التصنيف الأب (اختياري)</label>
            <select name="parent_id" class="border p-2 w-full rounded">
                <option value="">بدون أب</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}"
                        {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- الحالة -->
        <div class="mb-4 flex items-center gap-3">
            <label class="font-semibold">فعال:</label>
            <input type="checkbox"
                   name="is_active"
                   value="1"
                   {{ $category->is_active ? 'checked' : '' }}>
        </div>

        <!-- الصورة الحالية -->
        <div class="mb-4">
            <label class="block mb-2 font-semibold">الصورة الحالية</label>

            @if ($category->getFirstMediaUrl('categories'))
                <img src="{{ $category->getFirstMediaUrl('categories') }}"
                     class="h-28 rounded border mb-3">
            @else
                <p class="text-gray-500">لا توجد صورة حالياً</p>
            @endif
        </div>

        <!-- رفع صورة -->
        <div class="mb-4">
            <label class="block mb-2 font-semibold">رفع صورة جديدة من الجهاز</label>
            <input type="file"
                   name="image_file"
                   class="border p-2 w-full rounded">
        </div>

        <!-- رابط صورة -->
        <div class="mb-4">
            <label class="block mb-2 font-semibold">أو ضع رابط للصورة</label>
            <input type="text"
                   name="image_url"
                   class="border p-2 w-full rounded"
                   placeholder="https://example.com/image.jpg"
                   value="{{ old('image_url') }}">
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
            حفظ التعديلات
        </button>

    </form>

</div>

@endsection
