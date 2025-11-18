<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Local;
use Illuminate\Http\Request;

class LocalManagementController extends Controller
{
    public function index()
    {
        $locals = Local::orderBy('code')->paginate(20);
        return view('admin.locals.index', compact('locals'));
    }

    public function create()
    {
        return view('admin.locals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:locals,code',
            'name' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'type' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        Local::create([
            'code' => $request->code,
            'name' => $request->name,
            'building' => $request->building,
            'capacity' => $request->capacity,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.locals.index')
            ->with('success', 'Room created successfully.');
    }

    public function edit(Local $local)
    {
        return view('admin.locals.edit', compact('local'));
    }

    public function update(Request $request, Local $local)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:locals,code,' . $local->id,
            'name' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'type' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $local->update([
            'code' => $request->code,
            'name' => $request->name,
            'building' => $request->building,
            'capacity' => $request->capacity,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.locals.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Local $local)
    {
        $local->delete();

        return redirect()->route('admin.locals.index')
            ->with('success', 'Room deleted successfully.');
    }
}
