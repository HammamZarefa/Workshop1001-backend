@extends('layouts.main')

@section('title','Create Role')

@section('content')

    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg">

        <h1 class="text-3xl font-bold mb-6 border-b pb-3">Create Role</h1>

        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label class="block mb-2 font-semibold">Role Name</label>
                <input name="name" class="w-full border px-4 py-2 rounded-lg"
                       value="{{ old('name') }}" required>
            </div>

            <!-- Label -->
            <div>
                <label class="block mb-2 font-semibold">Label</label>
                <input name="label" class="w-full border px-4 py-2 rounded-lg"
                       value="{{ old('label') }}" required>
            </div>

            <!-- Permissions -->
            <div>
                <label class="block mb-2 font-semibold">Permissions</label>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($permissions as $permission)
                        <label class="flex items-center space-x-2 bg-gray-100 p-2 rounded-md">
                            <input type="checkbox" name="permissions[]"
                                   value="{{ $permission->id }}"
                                   class="w-4 h-4">
                            <span>{{ $permission->label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                Save Role
            </button>

        </form>

    </div>

@endsection
