<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActiveDaysCounterController extends Controller
{
    public function index()
    {
        // Data dummy untuk Active Days Counter
        // TODO: Ganti dengan query database yang sebenarnya
        $active_days = (object)[
            'remaining_days' => 45,
            'active_days_percentage' => 75,
            'enrollment_date' => Carbon::now()->subDays(15)->format('d M Y'),
            'expiry_date' => Carbon::now()->addDays(45)->format('d M Y'),
        ];

        return view('student.dashboard.components.active-days-counter', compact('active_days'));
    }
}

