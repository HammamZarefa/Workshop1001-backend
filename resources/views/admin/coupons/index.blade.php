@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Coupons</h1>
        <a href="{{ route('admin.coupons.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Create Coupon</a>
    </div>

    <div class="bg-white shadow rounded-lg p-4">
        <table class="min-w-full border">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="py-3 px-4 border">Name</th>
                    <th class="py-3 px-4 border">Code</th>
                    <th class="py-3 px-4 border">Type</th>
                    <th class="py-3 px-4 border">Value</th>
                    <th class="py-3 px-4 border">Start</th>
                    <th class="py-3 px-4 border">End</th>
                    <th class="py-3 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr class="border-b">
                    <td class="py-3 px-4">{{ $coupon->name }}</td>
                    <td class="py-3 px-4">{{ $coupon->code }}</td>
                    <td class="py-3 px-4">{{ ucfirst($coupon->type) }}</td>
                    <td class="py-3 px-4">{{ $coupon->value }}</td>
                    <td class="py-3 px-4">{{ $coupon->start_date }}</td>
                    <td class="py-3 px-4">{{ $coupon->expiration_date }}</td>
                    <td class="py-3 px-4 flex space-x-2">
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-blue-600">Edit</a>

                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Delete this coupon?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-3 px-4 text-center text-gray-500">
                        No coupons found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection
