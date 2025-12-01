@foreach ($children as $child)
    <li class="mb-2">
        @if ($child->getFirstMediaUrl('categories'))
            <div class="w-16 h-16 overflow-hidden rounded border mb-1 inline-block">
                <img src="{{ $child->getFirstMediaUrl('categories') }}"
                     class="w-full h-full object-cover">
            </div>
        @endif

        <span class="font-semibold">{{ $child->title }}</span>

        <div class="flex gap-2 mt-1">
            <a class="text-blue-600" href="{{ route('admin.categories.show', $child->id) }}">عرض</a>
            <a class="text-yellow-600" href="{{ route('admin.categories.edit', $child->id) }}">تعديل</a>

            <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟');">
                @csrf
                @method('DELETE')
                <button class="text-red-600">حذف</button>
            </form>
        </div>

        @if ($child->children->count())
            <ul class="ml-6 mt-2">
                @include('admin.categories.partials.tree', ['children' => $child->children])
            </ul>
        @endif
    </li>
@endforeach
