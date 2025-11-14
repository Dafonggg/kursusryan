<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatShortcutController extends Controller
{
    public function index()
    {
        // Data dummy untuk Chat Shortcut
        // TODO: Ganti dengan query database yang sebenarnya
        $chat_shortcut = (object)[
            'instructor_id' => 1,
            'instructor_name' => 'Budi Santoso',
            'instructor_avatar' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg'),
        ];

        return view('student.dashboard.components.chat-shortcut', compact('chat_shortcut'));
    }
}

