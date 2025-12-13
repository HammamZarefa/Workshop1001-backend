@extends('layouts.main')

@section('title', 'Add Permission')

@section('content')

    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg">

        <h1 class="text-3xl font-bold mb-6 border-b pb-3">Create Permission</h1>

        <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block mb-2 font-semibold">Permission Name</label>
                <input name="name" type="text"
                       class="w-full border rounded-lg px-4 py-2"
                       value="{{ old('name') }}" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Label</label>
                <input name="label" type="text"
                       class="w-full border rounded-lg px-4 py-2"
                       value="{{ old('label') }}" required>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                Save Permission
            </button>
        </form>

    </div>

@endsection
