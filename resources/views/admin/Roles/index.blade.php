@extends('layouts.main')

@section('title', 'Roles List')

@section('content')

    <div x-data="roleModal()" class="pb-10">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Roles</h1>
            @can('create', App\Models\Role::class)
                <button
                    @click="openCreateModal = true"
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

                        <!-- Edit button -->
                        @can('update', $role)
                            <button
                                @click="openEditModal({{ $role->id }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium px-3 py-1.5 rounded-md"
                            >
                                Edit
                            </button>
                        @endcan


                        <!-- Delete form -->
                        <form action="{{ route('admin.roles.destroy', $role->id) }}"
                              method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            @can('delete', $role)
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline-block">
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

                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


        <!-- Modal (Create + Edit) -->
        <div
            x-show="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
            x-transition
        >
            <div class="bg-white rounded-lg shadow-lg w-96 p-6" @click.away="closeModal">

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

                    <div class="flex justify-end space-x-2 mt-4">
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
                formData: {
                    id: '',
                    name: '',
                    label: ''
                },

                /** Open Create Modal */
                openCreateModal() {
                    this.isEdit = false;
                    this.formAction = "{{ route('admin.roles.store') }}";
                    this.formData = { name: '', label: '' };
                    this.showModal = true;
                },

                /** Open Edit Modal */
                openEditModal(role) {
                    this.isEdit = true;
                    this.formAction = `/admin/roles/${role.id}`;
                    this.formData = {
                        name: role.name,
                        label: role.label
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
