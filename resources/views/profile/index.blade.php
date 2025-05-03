@extends('layout')

@section('title', 'Profile')

@section('main')
<div class="container mt-4">
    <h1 class="mb-3">Profile</h1>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Hello, {{ $user->name }}!</h5>
            <p class="card-text">Your email is <strong>{{ $user->email }}</strong>.</p>
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row gap-2">
        <a class="btn btn-primary" href="{{ route('eventCreateForm') }}">Create Event</a>
        <a class="btn btn-secondary" href="{{ route('event.user') }}">Your Created Events</a>
        <a class="btn btn-success" href="{{ route('event.bookmark') }}">Your Bookmarked Events</a>
    </div>
</div>
@endsection
