<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        return Inertia::render('events/Index', [
            'events' => auth()->user()->events->map(function ($event) {
                $hours = $event->start->diffInHours($event->end);

                return [
                    'id'    => $event->id,
                    'title' => "{$event->class} {$hours}H",
                    'start' => $event->start->format('Y-m-d H:i'),
                    'end'   => $event->end->format('Y-m-d H:i'),
                    'class' => $event->class,
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        auth()->user()->events()->create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'class' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        auth()->user()->events()->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }
}
