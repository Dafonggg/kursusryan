<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class listingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listing(Request $request)
    {
        $query = Course::with(['owner', 'enrollments']);
        
        // Filter by mode if provided
        if ($request->has('mode') && $request->mode) {
            $query->where('mode', $request->mode);
        }
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }
        
        $courses = $query->latest()->paginate(12);
        
        return view('landing.daftar-kursus', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
