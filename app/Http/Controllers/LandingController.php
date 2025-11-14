<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Enums\CourseMode;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua courses dengan relasi owner dan enrollments
        $allCourses = Course::with(['owner', 'enrollments'])->latest()->get();
        
        // Kelompokkan berdasarkan mode
        $onlineCourses = Course::with(['owner', 'enrollments'])->online()->latest()->get();
        $offlineCourses = Course::with(['owner', 'enrollments'])->offline()->latest()->get();
        $hybridCourses = Course::with(['owner', 'enrollments'])->hybrid()->latest()->get();
        
        return view('landing.index', compact('allCourses', 'onlineCourses', 'offlineCourses', 'hybridCourses'));
    }

    /**
     * Display the topics detail page.
     */
    /**
     * Display the main page with all content combined.
     */
    public function main()
    {
        return view('landing.main2');
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
