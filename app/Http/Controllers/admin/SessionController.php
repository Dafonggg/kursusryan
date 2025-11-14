<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SessionController extends Controller
{
    /**
     * Show the form for creating a new session
     */
    public function create()
    {
        $courses = Course::all();
        $instructors = User::where('role', 'instructor')->get();
        
        return view('admin.sessions.create', compact('courses', 'instructors'));
    }

    /**
     * Store a newly created session
     */
    public function store(Request $request)
    {
        $rules = [
            'course_id' => 'required|exists:courses,id',
            'instructor_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'mode' => 'required|in:online,offline,hybrid',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
        ];

        // Conditional validation berdasarkan mode
        if (in_array($request->mode, ['online', 'hybrid'])) {
            $rules['meeting_url'] = 'required|url';
            $rules['meeting_platform'] = 'required|string|max:255';
        } else {
            $rules['meeting_url'] = 'nullable|url';
            $rules['meeting_platform'] = 'nullable|string|max:255';
        }

        if (in_array($request->mode, ['offline', 'hybrid'])) {
            $rules['location'] = 'required|string|max:255';
        } else {
            $rules['location'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        // Parse scheduled_at ke Carbon instance
        if (isset($validated['scheduled_at'])) {
            $validated['scheduled_at'] = Carbon::parse($validated['scheduled_at']);
        }

        CourseSession::create($validated);

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Sesi berhasil dibuat!');
    }

    /**
     * Display a listing of sessions
     */
    public function index()
    {
        $sessions = CourseSession::with(['course', 'instructor'])
            ->latest('scheduled_at')
            ->paginate(10);
        
        return view('admin.sessions.index', compact('sessions'));
    }
}

