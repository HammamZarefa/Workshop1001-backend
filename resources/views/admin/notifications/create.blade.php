@extends('layouts.main')

@section('content')
<div class="container">
    <h1 class="mb-4">Send Notification</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.notifications.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Channels</label>
            <div>
                <label>
                    <input type="checkbox" name="channels[]" value="database" checked>
                    Database
                </label>
                <label class="ms-3">
                    <input type="checkbox" name="channels[]" value="mail">
                    Mail
                </label>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Target Users</label>
            <select name="users[]" class="form-control" multiple>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Leave empty to send to all users</small>
        </div>

        <button class="btn btn-primary">Send Notification</button>
    </form>
</div>
@endsection
