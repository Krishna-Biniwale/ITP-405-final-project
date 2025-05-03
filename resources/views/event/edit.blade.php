@extends('layout')

@section('title', 'Edit Event')

@section('main')
    <div class="container mt-4">
        <h1 class="mb-4">Edit Event</h1>

        <form method="post" action="{{ route('event.update', $event->id) }}" class="border rounded p-4 shadow-sm bg-light">
            @csrf
            @method('PUT')
            <p>{{ $event->id }}</p>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ $event->title }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ $event->description }}">
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ $event->date }}">
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="time" id="time" name="time" class="form-control" value="{{ $event->time }}">
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" class="form-control" value="{{ $event->location }}">
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>

        <form method="POST" action="{{ route('event.destroy', ['id' => $event->id]) }}" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Event</button>
        </form>
    </div>
@endsection
