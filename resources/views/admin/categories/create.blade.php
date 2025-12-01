@extends('layouts.main')

@section('content')
<div class="p-6">

    <h1 class="text-xl font-bold mb-4">إضافة تصنيف جديد</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- اسم التصنيف -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold">اسم التصنيف</label>
            <input type="text" name="title" class="border p-2 w-full" required>
        </div>

        <!-- التصنيف الأب -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold">التصنيف الأب (اختياري)</label>
            <select name="parent_id" class="border p-2 w-full">
                <option value="">— بدون —</option>
                @foreach ($categories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                @endforeach
            </select>
        </div>

        <!-- مفعل -->
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1">
                <span>مفعل</span>
            </label>
        </div>

        <!-- رفع صورة من الجهاز -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold">رفع صورة من الجهاز (اختياري)</label>
            <input type="file" name="image_file" class="border p-2 w-full">
        </div>

        <!-- رابط الصورة -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold">أو رابط الصورة (اختياري)</label>
            <input type="url" name="image_url" class="border p-2 w-full" placeholder="https://example.com/image.jpg">
        </div>

        <!-- زر الحفظ -->
        <button class="bg-green-600 text-white px-4 py-2 rounded">حفظ</button>
    </form>

</div>
@endsection
