<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContinueLearningController extends Controller
{
    public function index()
    {
        // Data dummy untuk Continue Learning
        // TODO: Ganti dengan query database yang sebenarnya
        $continue_learning = (object)[
            'course_id' => 1,
            'course_name' => 'Laravel Advanced',
            'course_image' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
            'lesson_name' => 'Database Migration & Seeder',
            'lesson_id' => 5,
            'progress_percentage' => 65,
        ];

        return view('student.dashboard.components.continue-learning', compact('continue_learning'));
    }
}

