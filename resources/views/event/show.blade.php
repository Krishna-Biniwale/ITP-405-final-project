@extends('layout')

@section('title', $event->title)

@section('main')
    @php
        if (auth()->check()) {
            $userComment = $event->comments->where('user_id', auth()->user()->id)->first();
        }
    @endphp

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mt-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h1 class="card-title">{{ $event->title }}</h1>
                <p class="card-subtitle text-muted mb-2">Organized by: {{ $event->user->name }}</p>
                <p class="mb-1"><strong>Description:</strong> {{ $event->description }}</p>
                <p class="mb-1"><strong>Date:</strong> {{ date('F j, Y', strtotime($event->date)) }}</p>
                <p class="mb-1"><strong>Time:</strong> {{ date('g:i A', strtotime($event->time)) }}</p>
                <p class="mb-1"><strong>Location:</strong> {{ $event->location }}</p>
                
                @if (auth()->check() && $event->user_id == auth()->user()->id)
                    <a class="btn btn-outline-primary mt-2" href="{{ route('event.edit', $event->id) }}">Edit Event</a>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="card-title">Attendees</h2>

                @if ($event->attendees->isEmpty())
                    <p class="text-muted">There are no attendees yet.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @php
                            $attendeesWithComments = $event->attendees->map(function ($attendee) use ($event) {
                                $attendeeCommentTemp = $event->comments->where('user_id', $attendee->id)->first();
                                return [
                                    'attendee' => $attendee,
                                    'comment' => $attendeeCommentTemp
                                ];
                            })->sortByDesc(function ($item) {
                                return $item['comment'] ? $item['comment']->created_at : null;
                            });
                        @endphp

                        @foreach ($attendeesWithComments as $item)
                            @php
                                $attendee = $item['attendee'];
                                $attendeeComment = $item['comment'];
                            @endphp
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $attendee->name }}</strong>

                                        @if (auth()->check() && $event->user_id == auth()->user()->id)
                                            <form method="post" class="d-inline ms-2" action="{{ route('event.removeUser', ['eventId' => $event->id, 'userId' => $attendee->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                            </form>
                                        @elseif (auth()->check() && $attendee->id == auth()->user()->id && $event->attendees->contains(auth()->user()->id))
                                            <small class="text-muted d-block mt-1">
                                                Bookmarked on {{ $event->attendees->find(auth()->user()->id)->pivot->created_at->format('F jS, Y \a\t g:i A') }}
                                            </small>
                                        @endif

                                        @if ($attendeeComment && $attendeeComment->comment)
                                            <p class="mb-0 mt-2">
                                                <em>“{{ $attendeeComment->comment }}”</em><br>
                                                <small class="text-muted">Written on {{ $attendeeComment->created_at->format('F jS, Y \a\t g:i A') }}</small>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        @if (auth()->check() && $event->user_id != auth()->user()->id)
            @if (!$event->attendees->contains(auth()->user()->id))
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="post" action="{{ route('event.join', ['eventId' => $event->id]) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="comment" class="form-label">Optional: Leave a comment</label>
                                <textarea class="form-control" id="comment" name="comment" rows="4"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Join Event</button>
                        </form>
                    </div>
                </div>
            @else
                @if ($userComment)
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <form method="post" action="{{ route('event.updateComment', ['id' => $userComment->id]) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Edit your comment</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="4">{{ $userComment->comment }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Edit Comment</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="post" action="{{ route('event.leave', ['eventId' => $event->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Leave Event</button>
                        </form>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
