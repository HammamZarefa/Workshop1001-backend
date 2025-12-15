@extends('layouts.main')

@section('content')
<h2 class="text-2xl font-bold mb-4">System Settings</h2>

<table class="min-w-full bg-white rounded shadow">
    <tr class="border-b">
        <th class="p-3">Key</th>
        <th class="p-3">Type</th>
        <th class="p-3">Value</th>
        <th class="p-3">Action</th>
    </tr>

    @foreach($settings as $setting)
        <tr class="border-b">
            <td class="p-3">{{ $setting->key }}</td>
            <td class="p-3">{{ $setting->type }}</td>
            <td class="p-3">{{ Str::limit($setting->value, 50) }}</td>
            <td class="p-3">
                <a href="{{ route('admin.settings.edit', $setting) }}"
                   class="text-blue-600">Edit</a>
            </td>
        </tr>
    @endforeach
</table>
@endsection
