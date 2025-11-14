<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class detailController extends Controller
{
    /**
     * Display the course detail page.
     */
    public function detail(Course $course)
    {
        $course->load(['owner', 'materials', 'sessions', 'enrollments']);
        return view('landing.detail-kursus', compact('course'));
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
