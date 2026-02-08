<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $events = $user->events;

        $usedHours = $events
            ->groupBy('class')
            ->map(fn ($group) =>
            $group->sum(fn ($event) =>
            $event->start->diffInHours($event->end, false)
            )
            );

        return Inertia::render('events/Index', [
            'events' => $events->map(function ($event) {
                $hours = $event->start->diffInHours($event->end);

                return [
                    'id'       => $event->id,
                    'title'    => "{$event->class} {$hours}H",
                    'start'    => $event->start->format('Y-m-d H:i'),
                    'end'      => $event->end->format('Y-m-d H:i'),
                    'class'    => $event->class,
                    'duration' => $hours,
                ];
            }),

            'stats' => [
                'potA' => $this->potStats($user->totalAHours, $usedHours->get('PotA', 0)),
                'potB' => $this->potStats($user->totalBHours, $usedHours->get('PotB', 0)),
                'potC' => $this->potStats($user->totalCHours, $usedHours->get('PotC', 0)),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvent($request);

        $request->user()->events()->create($validated);

        return redirect()
            ->route('events.index')
            ->with('success', 'Evenement aangemaakt.');
    }

    public function update(Request $request, Event $event)
    {
        abort_if($event->user_id !== auth()->id(), 403);

        $validated = $this->validateEvent($request);

        $event->update($validated);

        return redirect()
            ->route('events.index')
            ->with('success', 'Evenement bijgewerkt.');
    }

    public function destroy(Event $event)
    {
        abort_if($event->user_id !== auth()->id(), 403);

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Evenement verwijderd.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate(
            [
                'class' => ['required', Rule::in(['PotA', 'PotB', 'PotC', 'Ziek'])],
                'start' => ['required', 'date_format:Y-m-d\TH:i'],
                'end'   => ['required', 'date_format:Y-m-d\TH:i', 'after:start'],
            ],
            [
                'class.required' => 'Categorie is verplicht.',
                'class.in' => 'Ongeldige categorie.',

                'start.required' => 'Starttijd is verplicht.',
                'start.date_format' => 'Starttijd is ongeldig.',

                'end.required' => 'Eindtijd is verplicht.',
                'end.date_format' => 'Eindtijd is ongeldig.',
                'end.after' => 'Eindtijd moet na de starttijd liggen.',
            ]
        );
    }

    private function potStats(int $total, int $used): array
    {
        return [
            'total' => $total,
            'used' => $used,
            'remaining' => $total - $used,
        ];
    }
}
