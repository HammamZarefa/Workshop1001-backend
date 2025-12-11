@extends('layouts.main')

@section('title', 'Roles List')

@section('content')

    <div x-data="roleModal()" class="pb-10">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Roles</h1>
            @can('create', App\Models\Role::class)
                <button
                    @click="openCreateModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md"
                >
                    Add Role
                </button>
            @endcan
        </div>

        <!-- Roles Table -->
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
            @foreach ($roles as $role)
                <tr class="border-b">
                    <td class="px-4 py-2 text-center">{{ $role->id }}</td>
                    <td class="px-4 py-2 text-center">{{ $role->name }}</td>
                    <td class="px-4 py-2 text-center">{{ $role->label }}</td>
                    <td class="px-4 py-2 text-center space-x-2">
                        @can('update', $role)
                            <button
                                @click='openEditModal(@json($role))'
                                class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium px-3 py-1.5 rounded-md"
                            >
                                Edit
                            </button>
                        @endcan

                        @can('delete', $role)
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-3 py-1.5 rounded-md"
                                    onclick="return confirm('Are you sure?')"
                                >
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Modal (Create + Edit) -->
        <div
            x-show="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center overflow-auto"
        >
            <div class="bg-white rounded-lg shadow-lg w-96 p-6 my-10" @click.away="closeModal">

                <h2 class="text-xl font-bold mb-4" x-text="isEdit ? 'Edit Role' : 'Add Role'"></h2>

                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Name -->
                    <label class="block font-medium mb-1">Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2 mb-4"
                           x-model="formData.name" required>

                    <!-- Label -->
                    <label class="block font-medium mb-1">Label</label>
                    <input type="text" name="label" class="w-full border rounded px-3 py-2 mb-4"
                           x-model="formData.label" required>

                    <!-- Permissions -->
                    <!-- Permissions -->
                    <label class="block font-medium mb-2">Permissions</label>

                    <div class="space-y-3 max-h-72 overflow-y-auto p-2 border rounded">

                        @foreach($permissions as $module => $perms)

                            <div x-data="{ open: false }" class="border rounded p-2">

                                <!-- Module Title -->
                                <button
                                    type="button"
                                    @click="open = !open"
                                    class="w-full flex justify-between items-center font-semibold text-left"
                                >
                                    <span class="capitalize">{{ $module }} Module</span>
                                    <span x-text="open ? '-' : '+'"></span>
                                </button>

                                <!-- Permissions under module -->
                                <div x-show="open" class="mt-2 pl-3 space-y-1">

                                    @foreach($perms as $permission)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   x-model="formData.permissions">
                                            <span>{{ $permission->label }}</span>
                                        </label>
                                    @endforeach

                                </div>

                            </div>

                        @endforeach

                    </div>


                    <!-- Buttons fixed at bottom -->
                    <div class="flex justify-end space-x-2 mt-4 sticky bottom-0 bg-white pt-2">
                        <button type="button" @click="closeModal"
                                class="px-4 py-2 bg-gray-300 rounded-md">
                            Cancel
                        </button>

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                            Save
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <!-- Alpine.js Logic -->
    <script>
        function roleModal() {
            return {
                showModal: false,
                isEdit: false,
                formAction: '',
                formData: { id: '', name: '', label: '', permissions: [] },

                /** Open Create Modal */
                openCreateModal() {
                    this.isEdit = false;
                    this.formAction = "{{ route('admin.roles.store') }}";
                    this.formData = { name: '', label: '', permissions: [] };
                    this.showModal = true;
                },

                /** Open Edit Modal */
                openEditModal(role) {
                    this.isEdit = true;
                    this.formAction = `/admin/roles/${role.id}`;

                    this.formData = {
                        id: role.id,
                        name: role.name,
                        label: role.label,
                        permissions: role.permissions ? role.permissions.map(p => p.id) : []
                    };

                    this.showModal = true;
                },


                closeModal() {
                    this.showModal = false;
                }
            }
        }
    </script>

@endsection
