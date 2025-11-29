@extends('layouts.main')

@section('title', 'Edit Product')

@section('content')

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-3">Edit Product</h1>

        <form action="{{ route('admin.products.update', $product->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <div class="leading-loose p-10 bg-white rounded shadow-xl space-y-6">

                <!-- Title -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Product Title</label>
                    <input class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400"
                           name="title"
                           type="text"
                           value="{{ old('title', $product->title) }}"
                           required>
                </div>

                <!-- Description -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Description</label>
                    <textarea class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400"
                              name="description"
                              rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Category -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Category</label>
                    <select name="category_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Price</label>
                    <input class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400"
                           name="price"
                           type="number"
                           step="0.01"
                           value="{{ old('price', $product->price) }}">
                </div>

                <!-- Stock -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Stock</label>
                    <input class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400"
                           name="stock"
                           type="number"
                           value="{{ old('stock', $product->stock) }}">
                </div>

                <!-- Featured Image -->
                <div> <label class="block mb-2 font-semibold text-gray-700">Featured Image</label>
                    @if($product->getFirstMediaUrl('featured'))
                        <img src="{{ $product->getFirstMediaUrl('featured') }}" class="w-32 h-32 mb-3 rounded object-cover" alt="Featured">
                    @endif <input type="file" name="featured" class="w-full border border-gray-300 rounded-lg px-4 py-2"> </div>


                <!-- Gallery Images -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Gallery Images</label>

                    <div class="flex flex-wrap gap-3 mb-3">
                        @foreach($product->getMedia('gallery') as $img)
                            <div class="relative inline-block" id="img-{{ $img->id }}">
                                <img src="{{ $img->getUrl() }}"
                                     class="w-16 h-16 object-cover rounded-md border shadow-sm"
                                     alt="img">

                                <button type="button"
                                        onclick="deleteImage({{ $img->id }})"
                                        class="absolute top-0 right-0 bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center cursor-pointer hover:bg-blue-800">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <input type="file" name="gallery[]" multiple
                           class="w-full border border-gray-300 rounded-lg px-4 py-2" />

                </div>

                <script>
                    const deleteRouteTemplate = "{{ route('admin.products.gallery.delete', ['media' => '__MEDIA_ID__']) }}";

                    async function deleteImage(id) {
                        if (!confirm("Delete this image?")) return;

                        const url = deleteRouteTemplate.replace('__MEDIA_ID__', id);

                        try {
                            const res = await fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            if (!res.ok) {
                                const text = await res.text().catch(()=>null);
                                console.error('Delete request failed', res.status, text);
                                alert('Failed to delete image (server error).');
                                return;
                            }

                            const data = await res.json();

                            if (data.success) {
                                const el = document.getElementById('img-' + id);
                                if (el) el.remove();
                            } else {
                                alert(data.message || 'Failed to delete image');
                            }
                        } catch (err) {
                            console.error('Delete error', err);
                            alert('Failed to delete image (network error).');
                        }
                    }
                </script>

                <!-- Submit Button -->
            <button type="submit"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg">
                Update Product
            </button>

        </form>

    </div>

@endsection
