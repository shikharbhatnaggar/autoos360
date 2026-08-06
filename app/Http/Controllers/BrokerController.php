<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    public function index()
    {
        $brokers = Broker::withCount('purchases')->orderBy('name')->paginate(20);

        return view('brokers.index', compact('brokers'));
    }

    public function create()
    {
        return view('brokers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'mobile' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        Broker::create($validated);

        return redirect()->route('brokers.index')->with('success', 'Broker added.');
    }

    public function edit(Broker $broker)
    {
        return view('brokers.edit', compact('broker'));
    }

    public function update(Request $request, Broker $broker)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'mobile' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $broker->update($validated);

        return redirect()->route('brokers.index')->with('success', 'Broker updated.');
    }

    public function destroy(Broker $broker)
    {
        $broker->delete();

        return redirect()->route('brokers.index')->with('success', 'Broker removed.');
    }
}