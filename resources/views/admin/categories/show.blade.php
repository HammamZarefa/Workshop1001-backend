@extends('layouts.main')

@section('content')
<div class="p-6">

    <h1 class="text-xl font-bold mb-4">تفاصيل التصنيف</h1>

    <div class="mb-4">
        <strong>الاسم:</strong> {{ $category->title }}
    </div>

    <div class="mb-4">
        <strong>الحالة:</strong>
        {{ $category->is_active ? 'مفعل' : 'غير مفعل' }}
    </div>

    @if ($category->getFirstMediaUrl('categories'))
        <div class="mb-4">
            <strong>الصورة:</strong><br>
            <img src="{{ $category->getFirstMediaUrl('categories') }}" class="h-32">
        </div>
    @endif

    <h3 class="font-bold mt-6 mb-2">التصنيفات الفرعية:</h3>

    @if ($category->children->count())
        @include('admin.categories.partials.tree', ['children' => $category->children])
    @else
        <p class="text-gray-600">لا توجد تصنيفات فرعية.</p>
    @endif

</div>
@endsection
