<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CertificateReadyController extends Controller
{
    public function index()
    {
        // Data dummy untuk Certificate Ready
        // TODO: Ganti dengan query database yang sebenarnya
        $ready_certificates = [
            (object)[
                'course_name' => 'Laravel Advanced',
                'course_category' => 'Web Development',
                'course_image' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
                'certificate_date' => Carbon::now()->subDays(10)->format('d M Y'),
                'certificate_url' => '#',
            ],
            (object)[
                'course_name' => 'Vue.js Mastery',
                'course_category' => 'Frontend Development',
                'course_image' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-3.jpg'),
                'certificate_date' => Carbon::now()->subDays(20)->format('d M Y'),
                'certificate_url' => '#',
            ],
        ];
        $ready_certificates_count = count($ready_certificates);

        return view('student.dashboard.components.certificate-ready', compact(
            'ready_certificates',
            'ready_certificates_count'
        ));
    }
}

