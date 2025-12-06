@extends('layouts.main')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Coupon</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.coupons.form', ['coupon' => $coupon])

            <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</div>
@endsection
