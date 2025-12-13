@extends('layouts.main')

@section('title', 'Permissions List')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Permissions</h1>

        <a href="{{ route('admin.permissions.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
            Add Permission
        </a>
    </div>

    <table class="w-full bg-white shadow rounded">
        <thead>
        <tr class="bg-gray-200 text-left">
            <th class="px-4 py-2 text-center">ID</th>
            <th class="px-4 py-2 text-center">Name</th>
            <th class="px-4 py-2 text-center">Label</th>
            <th class="px-4 py-2 text-center">Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($permissions as $permission)
            <tr class="border-b">
                <td class="px-4 py-2 text-center">{{ $permission->id }}</td>
                <td class="px-4 py-2 text-center">{{ $permission->name }}</td>
                <td class="px-4 py-2 text-center">{{ $permission->label }}</td>

                <td class="px-4 py-2 text-center space-x-2">
                    <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1.5 rounded-md">
                        Edit
                    </a>

                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}"
                          method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')

                        <button onclick="return confirm('Are you sure?')"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1.5 rounded-md">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
