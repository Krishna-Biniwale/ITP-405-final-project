@extends('layout') 

@section('title', 'View All Events')

@section('main')
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="mb-4">Events</h1>

        @if ($events->isEmpty())
            <div class="alert alert-info">
                There are no events yet. Click <a href="{{ route('event') }}" class="alert-link">here</a> to create one.
            </div>
        @else
            <div class="list-group">
                @foreach ($events as $event)
                    <a href="{{ route('event.show', $event->id) }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                       @if (auth()->check() && $event->user_id == auth()->user()->id)
                           list-group-item-primary
                       @elseif (auth()->check() && $event->attendees->contains(auth()->user()->id))
                           list-group-item-success
                       @else
                           list-group-item-secondary
                       @endif">
                        {{ $event->title }}
                        @if (auth()->check())
                            @if ($event->user_id == auth()->user()->id)
                                <span class="badge bg-primary">Organizer</span>
                            @elseif ($event->attendees->contains(auth()->user()->id))
                                <span class="badge bg-success">Attending</span>
                            @endif
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
