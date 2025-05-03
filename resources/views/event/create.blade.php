@extends('layout')

@section('title', 'New Event')

@section('main')
    <div class="container mt-4">
        <h1 class="mb-4">Create New Event</h1>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('event.create') }}" method="POST" class="border rounded p-4 shadow-sm bg-light">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}">
                @error('date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="time" name="time" id="time" class="form-control" value="{{ old('time') }}">
                @error('time')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
                @error('location')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
