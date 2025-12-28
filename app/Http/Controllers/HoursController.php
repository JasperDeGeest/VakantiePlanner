<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HoursController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('hours/Index', [
            'user' => $request->user()->only('totalAHours', 'totalBHours', 'totalCHours')
        ]);
    }

    public function update(Request $request)
    {
        $request->user()->update($request->validate([
            'totalAHours' => 'required|numeric|min:0',
            'totalBHours' => 'required|numeric|min:0',
            'totalCHours' => 'required|numeric|min:0',
        ]));

        return back();
    }
}
