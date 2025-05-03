<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Comment;
use Validator;

class EventController extends Controller
{
    public function index() {
        $events = Event::all();
        return view('event.index', compact('events'));
    }

    public function eventForm() {
        return view('event.create');
    }

    public function create(Request $request) {
        $validation = Validator::make($request->input(), [
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|after_or_equal:today',
            'time' => 'required',
            'location' => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $event = Event::create($request->all());
        return redirect()->route('event.show', ['id' => $event->id])->with('success', "Event \"{$event->title}\" was created successfully.");
    }

    public function show($eventId) {
        $event = Event::with(['user', 'attendees', 'comments'])->find($eventId);
        return view('event.show', compact('event'));
    }

    public function userEvents() {
        $events = Event::with(['user', 'attendees'])->where('user_id', auth()->user()->id)->get();
        return view('event.index', compact('events'));
    }

    public function bookmark() {
        $userId = auth()->user()->id;
        $events = Event::with(['user', 'attendees'])->whereHas('attendees', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        return view('event.index', compact('events'));
    }

    public function joinEvent($eventId) {
        $event = Event::find($eventId);
        $event->attendees()->attach(auth()->user()->id);
        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'event_id' => $event->id,
            'comment' => request('comment'),
        ]);
        return redirect()->route('event.show', ['id' => $event->id])->with('success', "You have joined event \"{$event->title}\" successfully.");
    }

    public function leaveEvent($eventId) {
        $event = Event::find($eventId);
        $event->attendees()->detach(auth()->user()->id);
        return redirect()->route('event.show', ['id' => $event->id])->with('success', "You have left event \"{$event->title}\" successfully.");
    }

    public function removeUser($eventId, $userId) {
        $event = Event::find($eventId);
        $event->attendees()->detach($userId);
        $user = User::find($userId);
        return redirect()->route('event.show', ['id' => $event->id])->with('success', "User \"{$user->name}\" was removed from event \"{$event->title}\" successfully.");
    }

    public function editForm($eventId) {
        $event = Event::find($eventId);
        return view('event.edit', compact('event'));
    }

    public function update($eventId, Request $request) {
        $event = Event::find($eventId);
        $validation = Validator::make($request->input(), [
            'title' => 'required',
            'description' => 'required',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $event->update($request->all());
        return redirect()->route('event.show', ['id' => $event->id])->with('success', "Event \"{$event->title}\" was updated successfully.");
    }

    public function destroy($eventId) {
        $event = Event::find($eventId);
        $event->delete();
        return redirect()->route('event.index')->with('success', "Event \"{$event->title}\" was deleted successfully.");
    }

    public function updateComment($commentId) {
        $comment = Comment::find($commentId);
        $event = Event::find($comment->event_id);
        $comment->update([
            'comment' => request('comment'),
            'created_at' => now()
        ]);
        return redirect()->route('event.show', ['id' => $event->id])->with('success', "You have updated your comment successfully.");
    }
}