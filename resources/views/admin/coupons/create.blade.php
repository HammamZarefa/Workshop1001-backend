@extends('layouts.main')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Create Coupon</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf

            @include('admin.coupons.form')

            <button class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</div>
@endsection
