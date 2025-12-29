<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $events = $user->events;

        $usedHours = $events->groupBy('class')->map(function ($group) {
            return $group->sum(fn($event) => $event->start->diffInHours($event->end, false));
        });

        return Inertia::render('events/Index', [
            'events' => $events->map(function ($event) {
                $hours = $event->start->diffInHours($event->end);
                return [
                    'id'    => $event->id,
                    'title' => "{$event->class} {$hours}H",
                    'start' => $event->start->format('Y-m-d H:i'),
                    'end'   => $event->end->format('Y-m-d H:i'),
                    'class' => $event->class,
                    'duration' => $hours,
                ];
            }),

            'stats' => [
                'potA' => [
                    'total' => $user->totalAHours,
                    'used' => $usedHours->get('PotA', 0),
                    'remaining' => $user->totalAHours - $usedHours->get('PotA', 0),
                ],
                'potB' => [
                    'total' => $user->totalBHours,
                    'used' => $usedHours->get('PotB', 0),
                    'remaining' => $user->totalBHours - $usedHours->get('PotB', 0),
                ],
                'potC' => [
                    'total' => $user->totalCHours,
                    'used' => $usedHours->get('PotC', 0),
                    'remaining' => $user->totalCHours - $usedHours->get('PotC', 0),
                ],
            ]
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

        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'class' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
