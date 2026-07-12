<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Attendance::whereDate('created_at', \Carbon\Carbon::today())
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage (Check In)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'major' => 'required|string|max:255',
        ]);

        $validated['check_in'] = now();

        $attendance = Attendance::create($validated);
        return response()->json($attendance, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        return response()->json($attendance);
    }

    /**
     * Update the specified resource in storage (Check Out / Edit)
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'check_out' => 'sometimes|boolean',
        ]);

        if (isset($validated['check_out']) && $validated['check_out'] === true) {
            $attendance->check_out = now();
        } else {
            $attendance->fill($request->only(['name', 'class', 'major']));
        }

        $attendance->save();
        return response()->json($attendance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return response()->json(null, 204);
    }
}
