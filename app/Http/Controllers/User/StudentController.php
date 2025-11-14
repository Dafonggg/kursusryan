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
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data real untuk Continue Learning
        $continue_learning = $this->getContinueLearning($user);
        
        // Data real untuk Next Session
        $next_session = $this->getNextSession($user);
        
        // Data real untuk Active Days Counter
        $active_days = $this->getActiveDays($user);
        
        // Data real untuk Payment Status
        $payment_status = $this->getPaymentStatus($user);
        
        // Data real untuk Certificate Ready
        $ready_certificates = $this->getReadyCertificates($user);
        $ready_certificates_count = count($ready_certificates);
        
        // Data real untuk Chat Shortcut
        $chat_shortcut = $this->getChatShortcut($user);

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
        $user = Auth::user();
        
        // Data real untuk Continue Learning
        $continue_learning = $this->getContinueLearning($user);
        
        // Data real untuk Active Days Counter
        $active_days = $this->getActiveDays($user);

        return view('student.dashboard.index', compact(
            'continue_learning',
            'active_days'
        ))->with('showMyCoursesOnly', true);
    }

    public function schedule()
    {
        $user = Auth::user();
        
        // Data real untuk Next Session
        $next_session = $this->getNextSession($user);

        return view('student.dashboard.index', compact(
            'next_session'
        ))->with('showScheduleOnly', true);
    }

    public function payment()
    {
        $user = Auth::user();
        
        // Data real untuk Payment Status
        $payment_status = $this->getPaymentStatus($user);

        return view('student.dashboard.index', compact(
            'payment_status'
        ))->with('showPaymentOnly', true);
    }

    public function certificate()
    {
        $user = Auth::user();
        
        // Data real untuk Certificate Ready
        $ready_certificates = $this->getReadyCertificates($user);
        $ready_certificates_count = count($ready_certificates);

        return view('student.dashboard.index', compact(
            'ready_certificates',
            'ready_certificates_count'
        ))->with('showCertificateOnly', true);
    }

    public function chat()
    {
        $user = Auth::user();
        
        // Data real untuk Chat Shortcut
        $chat_shortcut = $this->getChatShortcut($user);

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
     * Helper: Get Continue Learning Data
     */
    private function getContinueLearning($user)
    {
        $activeEnrollment = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->with(['course.materials' => function($query) {
                $query->orderBy('order');
            }])
            ->latest('started_at')
            ->first();
        
        if ($activeEnrollment && $activeEnrollment->course) {
            $course = $activeEnrollment->course;
            $materials = $course->materials;
            $totalMaterials = $materials->count();
            
            // Ambil materi pertama (atau terakhir jika ada tracking)
            $lastMaterial = $materials->first();
            
            // Hitung progress sederhana (bisa disesuaikan dengan sistem tracking yang lebih detail)
            $progressPercentage = $totalMaterials > 0 ? min(100, (int)(($totalMaterials / max($totalMaterials, 1)) * 50)) : 0;
            
            if ($lastMaterial) {
                return (object)[
                    'course_id' => $course->id,
                    'course_name' => $course->title,
                    'course_image' => $course->image ? Storage::url($course->image) : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
                    'lesson_name' => $lastMaterial->title,
                    'lesson_id' => $lastMaterial->id,
                    'progress_percentage' => $progressPercentage,
                ];
            }
        }
        
        return null;
    }

    /**
     * Helper: Get Next Session Data
     */
    private function getNextSession($user)
    {
        $nextSession = CourseSession::whereHas('course.enrollments', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', EnrollmentStatus::Active);
            })
            ->where('scheduled_at', '>=', now())
            ->with(['course'])
            ->orderBy('scheduled_at')
            ->first();
        
        if ($nextSession) {
            $course = $nextSession->course;
            $startTime = $nextSession->scheduled_at;
            $endTime = $startTime->copy()->addMinutes($nextSession->duration_minutes ?? 120);
            
            $isOnline = in_array(strtolower($nextSession->mode ?? ''), ['online', 'hybrid']);
            
            return (object)[
                'session_id' => $nextSession->id,
                'course_name' => $course->title,
                'course_image' => $course->image ? Storage::url($course->image) : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-2.jpg'),
                'session_name' => $nextSession->title,
                'session_date' => $startTime->format('d M Y'),
                'session_time' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i') . ' WIB',
                'session_mode' => ucfirst($nextSession->mode ?? 'Online'),
                'session_location' => $nextSession->location ?? ($isOnline ? 'Zoom Meeting' : 'Kelas Offline'),
                'session_link' => $nextSession->meeting_url ?? '#',
            ];
        }
        
        return null;
    }

    /**
     * Helper: Get Active Days Data
     */
    private function getActiveDays($user)
    {
        $activeEnrollment = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->latest('started_at')
            ->first();
        
        if ($activeEnrollment && $activeEnrollment->expires_at) {
            $remainingDays = max(0, now()->diffInDays($activeEnrollment->expires_at, false));
            $totalDays = $activeEnrollment->started_at && $activeEnrollment->expires_at 
                ? $activeEnrollment->started_at->diffInDays($activeEnrollment->expires_at) 
                : 90;
            $usedDays = $activeEnrollment->started_at 
                ? max(0, now()->diffInDays($activeEnrollment->started_at)) 
                : 0;
            $activeDaysPercentage = $totalDays > 0 ? min(100, (int)(($usedDays / $totalDays) * 100)) : 0;
            
            return (object)[
                'remaining_days' => $remainingDays,
                'active_days_percentage' => $activeDaysPercentage,
                'enrollment_date' => $activeEnrollment->started_at ? $activeEnrollment->started_at->format('d M Y') : '-',
                'expiry_date' => $activeEnrollment->expires_at->format('d M Y'),
            ];
        }
        
        return null;
    }

    /**
     * Helper: Get Payment Status Data
     */
    private function getPaymentStatus($user)
    {
        $lastPayment = Payment::whereHas('enrollment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest('created_at')
            ->first();
        
        if ($lastPayment) {
            $statusBadge = match($lastPayment->status->value) {
                'paid' => 'success',
                'pending' => 'warning',
                'failed' => 'danger',
                'refunded' => 'info',
                default => 'secondary'
            };
            
            return (object)[
                'payment_id' => $lastPayment->id,
                'payment_amount' => 'Rp ' . number_format($lastPayment->amount, 0, ',', '.'),
                'payment_status' => ucfirst($lastPayment->status->value),
                'payment_status_badge' => $statusBadge,
                'payment_date' => $lastPayment->paid_at ? $lastPayment->paid_at->format('d M Y') : $lastPayment->created_at->format('d M Y'),
                'payment_method' => ucfirst($lastPayment->method->value ?? 'Transfer'),
                'invoice_url' => route('student.payment') . '?payment=' . $lastPayment->id,
            ];
        }
        
        return null;
    }

    /**
     * Helper: Get Ready Certificates Data
     */
    private function getReadyCertificates($user)
    {
        $certificates = Certificate::whereHas('enrollment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['enrollment.course'])
            ->latest('issued_at')
            ->get();
        
        $ready_certificates = [];
        foreach ($certificates as $cert) {
            $course = $cert->enrollment->course;
            $ready_certificates[] = (object)[
                'course_name' => $course->title,
                'course_category' => 'Kursus', // Bisa disesuaikan jika ada kategori
                'course_image' => $course->image ? Storage::url($course->image) : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
                'certificate_date' => $cert->issued_at ? $cert->issued_at->format('d M Y') : '-',
                'certificate_url' => $cert->file_path ? Storage::url($cert->file_path) : '#',
            ];
        }
        
        return $ready_certificates;
    }

    /**
     * Helper: Get Chat Shortcut Data
     */
    private function getChatShortcut($user)
    {
        $activeEnrollment = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Active)
            ->with('course')
            ->latest('started_at')
            ->first();
        
        if ($activeEnrollment && $activeEnrollment->course && $activeEnrollment->course->owner_id) {
            $instructor = User::find($activeEnrollment->course->owner_id);
            if ($instructor) {
                $profile = $instructor->profile;
                $avatar = $profile && $profile->photo_path 
                    ? Storage::url($profile->photo_path) 
                    : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-3.jpg');
                
                return (object)[
                    'instructor_id' => $instructor->id,
                    'instructor_name' => $instructor->name,
                    'instructor_avatar' => $avatar,
                ];
            }
        }
        
        return null;
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
