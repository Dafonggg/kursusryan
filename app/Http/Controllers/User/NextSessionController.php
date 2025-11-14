<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NextSessionController extends Controller
{
    public function index()
    {
        // Data dummy untuk Next Session
        // TODO: Ganti dengan query database yang sebenarnya
        $next_session = (object)[
            'session_id' => 1,
            'course_name' => 'React Fundamentals',
            'course_image' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-2.jpg'),
            'session_name' => 'Sesi 4: Hooks & Context API',
            'session_date' => Carbon::now()->addDays(2)->format('d M Y'),
            'session_time' => '14:00 - 16:00 WIB',
            'session_mode' => 'Online',
            'session_location' => 'Zoom Meeting',
            'session_link' => 'https://zoom.us/j/123456789',
        ];

        return view('student.dashboard.components.next-session', compact('next_session'));
    }
}

