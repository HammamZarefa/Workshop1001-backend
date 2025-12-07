@extends('layouts.main')

@section('title','Edit Role')

@section('content')

    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg">

        <h1 class="text-3xl font-bold mb-6 border-b pb-3">Edit Role</h1>

        <form action="{{ route('admin.roles.update', $role->id) }}"
              method="POST"
              class="space-y-6">

            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block mb-2 font-semibold">Role Name</label>
                <input name="name" class="w-full border px-4 py-2 rounded-lg"
                       value="{{ old('name', $role->name) }}" required>
            </div>

            <!-- Label -->
            <div>
                <label class="block mb-2 font-semibold">Label</label>
                <input name="label" class="w-full border px-4 py-2 rounded-lg"
                       value="{{ old('label', $role->label) }}" required>
            </div>

            <!-- Permissions List -->
            <div>
                <label class="block mb-2 font-semibold">Permissions</label>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($permissions as $permission)
                        <label class="flex items-center space-x-2 bg-gray-100 p-2 rounded-md">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->id }}"
                                   class="w-4 h-4"
                                @checked($role->permissions->contains($permission->id))>
                            <span>{{ $permission->label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                Update Role
            </button>

        </form>

    </div>

@endsection
