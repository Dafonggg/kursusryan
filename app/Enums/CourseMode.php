<?php

namespace App\Enums;

enum CourseMode: string
{
    case Online = 'online';
    case Offline = 'offline';
    case Hybrid = 'hybrid';
}
