<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseSession;
use App\Models\RescheduleRequest;
use App\Models\UserProfile;
use App\Models\Payment;
use App\Models\Certificate;
use App\Enums\RescheduleStatus;
use App\Enums\EnrollmentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        // Data dummy untuk Continue Learning
        $continue_learning = (object)[
            'course_id' => 1,
            'course_name' => 'Laravel Advanced',
            'course_image' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
            'lesson_name' => 'Database Migration & Seeder',
            'lesson_id' => 5,
            'progress_percentage' => 65,
        ];

        // Data dummy untuk Next Session
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

        // Data dummy untuk Active Days Counter
        $active_days = (object)[
            'remaining_days' => 45,
            'active_days_percentage' => 75,
            'enrollment_date' => Carbon::now()->subDays(15)->format('d M Y'),
            'expiry_date' => Carbon::now()->addDays(45)->format('d M Y'),
        ];

        // Data dummy untuk Payment Status
        $payment_status = (object)[
            'payment_id' => 1,
            'payment_amount' => 'Rp 2.500.000',
            'payment_status' => 'Paid',
            'payment_status_badge' => 'success',
            'payment_date' => Carbon::now()->subDays(5)->format('d M Y'),
            'payment_method' => 'Bank Transfer',
            'invoice_url' => '#',
        ];

        // Data dummy untuk Certificate Ready
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

        // Data dummy untuk Chat Shortcut
        $chat_shortcut = (object)[
            'instructor_id' => 1,
            'instructor_name' => 'Budi Santoso',
            'instructor_avatar' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg'),
        ];

        return view('student.dashboard.index', compact(
            'continue_learning',
            'next_session',
            'active_days',
            'payment_status',
            'ready_certificates',
            'ready_certificates_count',
            'chat_shortcut'
        ));
    }

    public function myCourses()
    {
        // Data dummy untuk Continue Learning
        $continue_learning = (object)[
            'course_id' => 1,
            'course_name' => 'Laravel Advanced',
            'course_image' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
            'lesson_name' => 'Database Migration & Seeder',
            'lesson_id' => 5,
            'progress_percentage' => 65,
        ];

        // Data dummy untuk Active Days Counter
        $active_days = (object)[
            'remaining_days' => 45,
            'active_days_percentage' => 75,
            'enrollment_date' => Carbon::now()->subDays(15)->format('d M Y'),
            'expiry_date' => Carbon::now()->addDays(45)->format('d M Y'),
        ];
        


        return view('student.dashboard.index', compact(
            'continue_learning',
            'active_days'
        ))->with('showMyCoursesOnly', true);
    }

    public function schedule()
    {
        // Data dummy untuk Next Session
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

        return view('student.dashboard.index', compact(
            'next_session'
        ))->with('showScheduleOnly', true);
    }

    public function payment()
    {
        // Data dummy untuk Payment Status
        $payment_status = (object)[
            'payment_id' => 1,
            'payment_amount' => 'Rp 2.500.000',
            'payment_status' => 'Paid',
            'payment_status_badge' => 'success',
            'payment_date' => Carbon::now()->subDays(5)->format('d M Y'),
            'payment_method' => 'Bank Transfer',
            'invoice_url' => '#',
        ];

        return view('student.dashboard.index', compact(
            'payment_status'
        ))->with('showPaymentOnly', true);
    }

    public function certificate()
    {
        // Data dummy untuk Certificate Ready
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

        return view('student.dashboard.index', compact(
            'ready_certificates',
            'ready_certificates_count'
        ))->with('showCertificateOnly', true);
    }

    public function chat()
    {
        // Data dummy untuk Chat Shortcut
        $chat_shortcut = (object)[
            'instructor_id' => 1,
            'instructor_name' => 'Budi Santoso',
            'instructor_avatar' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg'),
        ];

        return view('student.dashboard.index', compact(
            'chat_shortcut'
        ))->with('showChatOnly', true);
    }

    /**
     * Menampilkan materi kursus yang diikuti student
     */
    public function materials(Request $request, $courseSlug = null)
    {
        $user = Auth::user();
        
        // Jika ada courseSlug, tampilkan materi untuk kursus tertentu
        if ($courseSlug) {
            $course = Course::where('slug', $courseSlug)->firstOrFail();
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('status', EnrollmentStatus::Active)
                ->firstOrFail();
            
            $materials = CourseMaterial::where('course_id', $course->id)
                ->orderBy('order')
                ->get();
            
            return view('student.materials.index', compact('course', 'materials', 'enrollment'));
        }
        
        // Jika tidak ada courseSlug, tampilkan semua kursus yang diikuti
        $enrollments = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->with(['course.materials' => function($query) {
                $query->orderBy('order');
            }])
            ->get();
        
        return view('student.materials.courses', compact('enrollments'));
    }

    /**
     * Menampilkan halaman reschedule
     */
    public function reschedule()
    {
        $user = Auth::user();
        
        // Ambil semua session dari kursus yang diikuti student
        $enrollments = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->with(['course.sessions' => function($query) {
                $query->where('scheduled_at', '>=', now())
                    ->orderBy('scheduled_at');
            }])
            ->get();
        
        // Ambil reschedule requests yang sudah dibuat student
        $rescheduleRequests = RescheduleRequest::where('requested_by', $user->id)
            ->with(['session.course', 'decider'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('student.reschedule.index', compact('enrollments', 'rescheduleRequests'));
    }

    /**
     * Menyimpan reschedule request
     */
    public function storeReschedule(Request $request)
    {
        $request->validate([
            'course_session_id' => 'required|exists:course_sessions,id',
            'proposed_at' => 'required|date|after:now',
            'reason' => 'required|string|max:500',
        ]);
        
        $user = Auth::user();
        
        // Cek apakah session tersebut milik kursus yang diikuti student
        $session = CourseSession::findOrFail($request->course_session_id);
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $session->course_id)
            ->where('status', EnrollmentStatus::Active)
            ->firstOrFail();
        
        // Cek apakah sudah ada reschedule request yang pending untuk session ini
        $existingRequest = RescheduleRequest::where('course_session_id', $request->course_session_id)
            ->where('requested_by', $user->id)
            ->where('status', RescheduleStatus::Pending)
            ->first();
        
        if ($existingRequest) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan reschedule untuk sesi ini. Tunggu persetujuan terlebih dahulu.');
        }
        
        RescheduleRequest::create([
            'course_session_id' => $request->course_session_id,
            'requested_by' => $user->id,
            'proposed_at' => $request->proposed_at,
            'reason' => $request->reason,
            'status' => RescheduleStatus::Pending,
        ]);
        
        return redirect()->route('student.reschedule')->with('success', 'Permintaan reschedule berhasil diajukan.');
    }

    /**
     * Menampilkan halaman profil
     */
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        return view('student.profile.index', compact('user', 'profile'));
    }

    /**
     * Update profil student
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'kk' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);
        
        // Update user name
        $user->update([
            'name' => $request->name,
        ]);
        
        // Update atau create profile
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);
        
        $profile->phone = $request->phone;
        $profile->address = $request->address;
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($profile->photo_path) {
                Storage::disk('public')->delete($profile->photo_path);
            }
            $profile->photo_path = $request->file('photo')->store('profiles', 'public');
        }
        
        // Handle KTP upload
        if ($request->hasFile('ktp')) {
            if ($profile->ktp_path) {
                Storage::disk('public')->delete($profile->ktp_path);
            }
            $profile->ktp_path = $request->file('ktp')->store('documents', 'public');
        }
        
        // Handle KK upload
        if ($request->hasFile('kk')) {
            if ($profile->kk_path) {
                Storage::disk('public')->delete($profile->kk_path);
            }
            $profile->kk_path = $request->file('kk')->store('documents', 'public');
        }
        
        $profile->save();
        
        return redirect()->route('student.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
