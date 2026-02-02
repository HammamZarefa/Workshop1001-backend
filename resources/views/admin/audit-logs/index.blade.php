@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Audit Logs</h1>


            <a href="{{ route('admin.audit-logs.export', request()->query()) }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Export CSV
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" class="bg-white p-4 rounded shadow mb-6 flex flex-wrap items-center gap-2">
            <select name="action" class="border rounded px-2 py-1 text-sm">
                <option value="">All Actions</option>
                <option value="create" @selected(request('action') == 'create')>Create</option>
                <option value="update" @selected(request('action') == 'update')>Update</option>
                <option value="delete" @selected(request('action') == 'delete')>Delete</option>
            </select>

            <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-2 py-1 text-sm">
            <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-2 py-1 text-sm">

            <button class="bg-blue-600 text-white rounded px-3 py-1 text-sm hover:bg-blue-700">
                Filter
            </button>
            <a href="{{ route('admin.audit-logs.index') }}"
               class="bg-gray-500 text-white rounded px-3 py-1 text-sm hover:bg-gray-600">
                Clear
            </a>

        </form>


        {{-- Table --}}
        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Admin</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Resource</th>
                    <th class="p-3">ID</th>
                    <th class="p-3">IP</th>
                    <th class="p-3">Date</th>
                </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    <tr class="border-t">
                        <td class="p-3">{{ $log->admin->email }}</td>
                        <td class="p-3">
                        <span class="px-2 py-1 rounded text-white
                            {{ $log->action === 'delete' ? 'bg-red-600' : 'bg-blue-600' }}">
                            {{ strtoupper($log->action) }}
                        </span>
                        </td>
                        <td class="p-3">{{ $log->resource }}</td>
                        <td class="p-3">{{ $log->resource_id }}</td>
                        <td class="p-3">{{ $log->ip_address }}</td>
                        <td class="p-3">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            No audit logs found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
@endsection
