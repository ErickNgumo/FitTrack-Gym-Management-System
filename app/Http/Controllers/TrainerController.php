<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::withCount('members')->orderBy('name')->paginate(20);
        return view('trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('trainers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'email'      => 'nullable|email|max:150|unique:trainers',
            'speciality' => 'nullable|string|max:100',
            'bio'        => 'nullable|string',
            'status'     => 'required|in:active,inactive',
        ]);

        $trainer = Trainer::create($validated);

        return redirect()
            ->route('trainers.index')
            ->with('success', "Trainer {$trainer->name} added.");
    }

    public function edit(Trainer $trainer)
    {
        return view('trainers.edit', compact('trainer'));
    }

    public function update(Request $request, Trainer $trainer)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'email'      => "nullable|email|max:150|unique:trainers,email,{$trainer->id}",
            'speciality' => 'nullable|string|max:100',
            'bio'        => 'nullable|string',
            'status'     => 'required|in:active,inactive',
        ]);

        $trainer->update($validated);

        return redirect()
            ->route('trainers.index')
            ->with('success', 'Trainer updated.');
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->update(['status' => 'inactive']);
        return redirect()->route('trainers.index')->with('success', 'Trainer deactivated.');
    }
}
