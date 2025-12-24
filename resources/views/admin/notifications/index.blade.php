@extends('layouts.main')

@section('content')
<div class="container">
    <h1 class="mb-4">Notification History</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Notification History</h1>

    <a href="{{ route('admin.notifications.create') }}"
       class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i>
        New Notification
    </a>
</div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Message</th>
                <th>Channels</th>
                <th>Sent To</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->title }}</td>
                    <td>{{ $log->message }}</td>
                    <td>{{ implode(', ', $log->channels) }}</td>
                    <td>{{ $log->sent_to }}</td>
                    <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $logs->links() }}
</div>
@endsection
