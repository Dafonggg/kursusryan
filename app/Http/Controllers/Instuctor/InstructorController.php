<?php

namespace App\Http\Controllers\Instuctor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\CourseMaterial;
use App\Models\Attendance;
use App\Models\RescheduleRequest;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Certificate;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use App\Models\UserProfile;
use App\Enums\RescheduleStatus;
use App\Enums\AttendanceStatus;
use App\Enums\EnrollmentStatus;
use App\Enums\MaterialType;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    public function index()
    {
        $instructorId = Auth::id();
        
        // Today Sessions - sesi yang dijadwalkan hari ini
        $today_sessions = $this->getTodaySessions();
        $today_sessions_count = count($today_sessions);

        // Tomorrow Sessions
        $tomorrow_sessions = $this->getTomorrowSessions();
        $tomorrow_sessions_count = count($tomorrow_sessions);

        // My Courses - kursus yang diajar oleh instruktur ini
        $my_courses = $this->getMyCourses();
        $my_courses_count = count($my_courses);

        // Attendance Pending - sesi yang sudah lewat tapi belum ada absensi
        $attendance_pending_sessions = $this->getAttendancePendingSessions();
        $pending_attendance_count = count($attendance_pending_sessions);

        // Reschedule Pending
        $reschedule_requests = $this->getRescheduleRequests();
        $pending_reschedule_count = count($reschedule_requests);

        // Latest Messages
        $latest_messages = $this->getLatestMessages();
        $unread_messages_count = $this->getUnreadMessagesCount();

        return view('instructor.dashboard.index', compact(
            'today_sessions',
            'today_sessions_count',
            'tomorrow_sessions',
            'tomorrow_sessions_count',
            'my_courses',
            'my_courses_count',
            'attendance_pending_sessions',
            'pending_attendance_count',
            'reschedule_requests',
            'pending_reschedule_count',
            'latest_messages',
            'unread_messages_count'
        ));
    }

    public function sessions(Request $request)
    {
        // Data untuk halaman Sesi Saya (berisi my-courses + today-sessions)
        $my_courses = $this->getMyCourses();
        $my_courses_count = count($my_courses);
        
        // Filter berdasarkan tanggal jika ada
        $dateFilter = $request->get('date');
        $sessions = $this->getSessionsByDate($dateFilter);
        
        $today_sessions = $this->getTodaySessions();
        $today_sessions_count = count($today_sessions);

        return view('instructor.dashboard.sessions', compact(
            'my_courses',
            'my_courses_count',
            'today_sessions',
            'today_sessions_count',
            'sessions',
            'dateFilter'
        ));
    }

    /**
     * Get sessions by date filter
     */
    private function getSessionsByDate($dateFilter = null)
    {
        $instructorId = Auth::id();
        $query = CourseSession::where('instructor_id', $instructorId)
            ->with(['course', 'attendances'])
            ->orderBy('scheduled_at', 'desc');
        
        if ($dateFilter) {
            $query->whereDate('scheduled_at', $dateFilter);
        }
        
        $sessions = $query->get();
        
        return $sessions->map(function ($session) {
            $startTime = Carbon::parse($session->scheduled_at)->format('H:i');
            $endTime = Carbon::parse($session->scheduled_at)
                ->addMinutes($session->duration_minutes ?? 90)
                ->format('H:i');
            
            $now = Carbon::now();
            $sessionTime = Carbon::parse($session->scheduled_at);
            $sessionEnd = $sessionTime->copy()->addMinutes($session->duration_minutes ?? 90);
            
            $status = 'Upcoming';
            $badge = 'warning';
            if ($now->between($sessionTime, $sessionEnd)) {
                $status = 'Ongoing';
                $badge = 'success';
            } elseif ($now->greaterThanOrEqualTo($sessionEnd)) {
                // Gunakan greaterThanOrEqualTo agar status langsung berubah ke Completed saat waktu sama
                $status = 'Completed';
                $badge = 'info';
            }
            
            // Ambil semua peserta yang terdaftar di kursus ini
            $enrollments = Enrollment::where('course_id', $session->course_id)
                ->where('status', EnrollmentStatus::Active)
                ->get();
            
            $participantCount = $enrollments->count();
            
            // Cek apakah semua peserta sudah punya absensi
            $attendanceUserIds = $session->attendances->pluck('user_id')->unique();
            $enrollmentUserIds = $enrollments->pluck('user_id')->unique();
            
            // Absensi lengkap jika semua peserta sudah punya absensi
            // Cek apakah ada peserta yang belum punya absensi
            $missingAttendance = $enrollmentUserIds->diff($attendanceUserIds);
            $attendanceComplete = $missingAttendance->isEmpty() && $participantCount > 0;
            
            return (object)[
                'id' => $session->id,
                'course_name' => $session->course->title ?? 'N/A',
                'title' => $session->title ?? 'Sesi ' . $session->id,
                'session_date' => Carbon::parse($session->scheduled_at)->format('d M Y'),
                'session_time' => $startTime . ' - ' . $endTime,
                'session_duration' => ($session->duration_minutes ?? 90) . ' menit',
                'participant_count' => $participantCount,
                'session_status' => $status,
                'status_badge' => $badge,
                'mode' => $session->mode,
                'location' => $session->location,
                'meeting_url' => $session->meeting_url,
                'attendance_complete' => $attendanceComplete,
                'session_end_datetime' => $sessionEnd->format('Y-m-d H:i:s'),
                'session_end_timestamp' => $sessionEnd->timestamp,
            ];
        })->toArray();
    }

    public function courses()
    {
        // Data untuk halaman Kursus Saya (berisi my-courses)
        $my_courses = $this->getMyCourses();
        $my_courses_count = count($my_courses);

        return view('instructor.dashboard.courses', compact(
            'my_courses',
            'my_courses_count'
        ));
    }

    public function attendance()
    {
        // Data untuk halaman Absensi (berisi attendance-pending)
        $attendance_pending_sessions = $this->getAttendancePendingSessions();
        $pending_attendance_count = count($attendance_pending_sessions);

        return view('instructor.dashboard.attendance', compact(
            'attendance_pending_sessions',
            'pending_attendance_count'
        ));
    }

    public function reschedule()
    {
        // Data untuk halaman Reschedule Request (berisi reschedule-pending)
        $reschedule_requests = $this->getRescheduleRequests();
        $pending_reschedule_count = count($reschedule_requests);

        return view('instructor.dashboard.reschedule', compact(
            'reschedule_requests',
            'pending_reschedule_count'
        ));
    }

    public function messages()
    {
        // Data untuk halaman Chat (berisi latest-messages)
        $latest_messages = $this->getLatestMessages();
        $unread_messages_count = $this->getUnreadMessagesCount();

        return view('instructor.dashboard.messages', compact(
            'latest_messages',
            'unread_messages_count'
        ));
    }

    /**
     * Menampilkan detail conversation
     */
    public function showChat($conversationId)
    {
        $instructorId = Auth::id();
        
        $conversation = Conversation::whereHas('participants', function($query) use ($instructorId) {
                $query->where('user_id', $instructorId);
            })
            ->with(['participants', 'messages.user'])
            ->findOrFail($conversationId);
        
        return view('instructor.dashboard.chat-show', compact('conversation'));
    }

    /**
     * Membuat conversation baru atau mendapatkan yang sudah ada
     */
    public function createOrGetConversation(Request $request)
    {
        $instructorId = Auth::id();
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        $otherUser = User::findOrFail($request->user_id);
        
        // Cek apakah sudah ada conversation
        $conversation = Conversation::whereHas('participants', function($query) use ($instructorId) {
                $query->where('user_id', $instructorId);
            })
            ->whereHas('participants', function($query) use ($otherUser) {
                $query->where('user_id', $otherUser->id);
            })
            ->first();
        
        if (!$conversation) {
            $conversation = Conversation::create([
                'title' => 'Chat dengan ' . $otherUser->name,
            ]);
            $conversation->participants()->attach([$instructorId, $otherUser->id]);
        }
        
        return redirect()->route('instructor.chat.show', $conversation->id);
    }

    /**
     * Mengirim pesan
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $instructorId = Auth::id();
        
        // Verify instructor is participant
        $conversation = Conversation::whereHas('participants', function($query) use ($instructorId) {
                $query->where('user_id', $instructorId);
            })
            ->findOrFail($conversationId);
        
        $request->validate([
            'body' => 'required|string|max:5000',
        ]);
        
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $instructorId,
            'body' => $request->body,
        ]);
        
        return redirect()->route('instructor.chat.show', $conversation->id)
            ->with('success', 'Pesan berhasil dikirim!');
    }

    // Helper methods untuk mendapatkan data dari database
    private function getTodaySessions()
    {
        $instructorId = Auth::id();
        $today = Carbon::today();
        
        $sessions = CourseSession::where('instructor_id', $instructorId)
            ->whereDate('scheduled_at', $today)
            ->with(['course', 'attendances'])
            ->orderBy('scheduled_at')
            ->get();
        
        return $sessions->map(function ($session) {
            $startTime = Carbon::parse($session->scheduled_at)->format('H:i');
            $endTime = Carbon::parse($session->scheduled_at)
                ->addMinutes($session->duration_minutes ?? 90)
                ->format('H:i');
            
            $now = Carbon::now();
            $sessionTime = Carbon::parse($session->scheduled_at);
            $sessionEnd = $sessionTime->copy()->addMinutes($session->duration_minutes ?? 90);
            
            $status = 'Upcoming';
            $badge = 'warning';
            if ($now->between($sessionTime, $sessionEnd)) {
                $status = 'Ongoing';
                $badge = 'success';
            } elseif ($now->greaterThanOrEqualTo($sessionEnd)) {
                // Gunakan greaterThanOrEqualTo agar status langsung berubah ke Completed saat waktu sama
                $status = 'Completed';
                $badge = 'info';
            }
            
            // Ambil semua peserta yang terdaftar di kursus ini
            $enrollments = Enrollment::where('course_id', $session->course_id)
                ->where('status', EnrollmentStatus::Active)
                ->get();
            
            $participantCount = $enrollments->count();
            
            // Cek apakah semua peserta sudah punya absensi
            $attendanceUserIds = $session->attendances->pluck('user_id')->unique();
            $enrollmentUserIds = $enrollments->pluck('user_id')->unique();
            
            // Absensi lengkap jika semua peserta sudah punya absensi
            // Cek apakah ada peserta yang belum punya absensi
            $missingAttendance = $enrollmentUserIds->diff($attendanceUserIds);
            $attendanceComplete = $missingAttendance->isEmpty() && $participantCount > 0;
            
            return (object)[
                'id' => $session->id,
                'course_name' => $session->course->title ?? 'N/A',
                'session_time' => $startTime . ' - ' . $endTime,
                'session_duration' => ($session->duration_minutes ?? 90) . ' menit',
                'participant_count' => $participantCount,
                'max_participants' => null,
                'session_status' => $status,
                'status_badge' => $badge,
                'attendance_complete' => $attendanceComplete,
                'session_end_timestamp' => $sessionEnd->timestamp,
                'session_end_datetime' => $sessionEnd->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }

    private function getTomorrowSessions()
    {
        $instructorId = Auth::id();
        $tomorrow = Carbon::tomorrow();
        
        $sessions = CourseSession::where('instructor_id', $instructorId)
            ->whereDate('scheduled_at', $tomorrow)
            ->with('course')
            ->orderBy('scheduled_at')
            ->get();
        
        return $sessions->map(function ($session) {
            $startTime = Carbon::parse($session->scheduled_at)->format('H:i');
            $endTime = Carbon::parse($session->scheduled_at)
                ->addMinutes($session->duration_minutes ?? 90)
                ->format('H:i');
            
            $participantCount = Enrollment::where('course_id', $session->course_id)
                ->where('status', EnrollmentStatus::Active)
                ->count();
            
            return (object)[
                'course_name' => $session->course->title ?? 'N/A',
                'session_time' => $startTime . ' - ' . $endTime,
                'participant_count' => $participantCount,
            ];
        })->toArray();
    }

    private function getMyCourses()
    {
        $instructorId = Auth::id();
        
        // Ambil kursus yang memiliki sesi dengan instructor_id = $instructorId
        $courses = Course::whereHas('sessions', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->withCount([
            'sessions' => function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            },
            'enrollments' => function ($query) {
                $query->where('status', EnrollmentStatus::Active);
            }
        ])
        ->get();
        
        return $courses->map(function ($course) {
            return (object)[
                'course_id' => $course->id,
                'course_slug' => $course->slug,
                'course_name' => $course->title,
                'course_category' => 'Course',
                'course_image' => $course->image ? asset('storage/' . $course->image) : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/stock/600x400/img-1.jpg'),
                'active_participants' => $course->enrollments_count ?? 0,
                'total_sessions' => $course->sessions_count ?? 0,
            ];
        })->toArray();
    }

    private function getAttendancePendingSessions()
    {
        $instructorId = Auth::id();
        $now = Carbon::now();
        
        // Sesi yang sudah selesai (waktu akhir sesi sudah lewat) tapi belum ada absensi untuk semua peserta
        $sessions = CourseSession::where('instructor_id', $instructorId)
            ->with(['course', 'attendances'])
            ->get()
            ->filter(function ($session) use ($now) {
                // Cek apakah waktu akhir sesi sudah lewat
                $sessionEnd = Carbon::parse($session->scheduled_at)
                    ->addMinutes($session->duration_minutes ?? 90);
                
                // Jika sesi belum selesai (waktu sekarang masih kurang dari waktu akhir), skip
                // Gunakan lessThan saja agar saat waktu sama dengan waktu akhir sudah bisa input
                if ($now->lessThan($sessionEnd)) {
                    return false;
                }
                
                // Cek apakah semua peserta sudah punya absensi
                $enrollments = Enrollment::where('course_id', $session->course_id)
                    ->where('status', EnrollmentStatus::Active)
                    ->pluck('user_id')
                    ->unique();
                
                // Jika tidak ada peserta, skip
                if ($enrollments->isEmpty()) {
                    return false;
                }
                
                $attendanceUserIds = $session->attendances->pluck('user_id')->unique();
                
                // Jika ada peserta yang belum punya absensi, return true
                return $enrollments->diff($attendanceUserIds)->isNotEmpty();
            })
            ->take(10);
        
        return $sessions->map(function ($session) {
            $sessionDate = Carbon::parse($session->scheduled_at);
            $startTime = $sessionDate->format('H:i');
            $endTime = $sessionDate->copy()
                ->addMinutes($session->duration_minutes ?? 90)
                ->format('H:i');
            
            $participantCount = Enrollment::where('course_id', $session->course_id)
                ->where('status', EnrollmentStatus::Active)
                ->count();
            
            return (object)[
                'session_id' => $session->id,
                'session_name' => $session->title ?? 'Sesi ' . $session->id,
                'course_name' => $session->course->title ?? 'N/A',
                'session_date' => $sessionDate->format('d M Y'),
                'session_time' => $startTime . ' - ' . $endTime,
                'participant_count' => $participantCount,
            ];
        })->toArray();
    }

    private function getRescheduleRequests()
    {
        $instructorId = Auth::id();
        
        $requests = RescheduleRequest::whereHas('session', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->where('status', RescheduleStatus::Pending)
        ->with(['session.course', 'requester'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return $requests->map(function ($request) {
            $proposedAt = Carbon::parse($request->proposed_at);
            
            return (object)[
                'request_id' => $request->id,
                'student_name' => $request->requester->name ?? 'N/A',
                'student_email' => $request->requester->email ?? 'N/A',
                'student_avatar' => asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-1.jpg'),
                'session_name' => $request->session->title ?? 'Sesi ' . $request->session->id,
                'course_name' => $request->session->course->title ?? 'N/A',
                'new_date' => $proposedAt->format('d M Y'),
                'new_time' => $proposedAt->format('H:i'),
            ];
        })->toArray();
    }

    private function getLatestMessages()
    {
        $instructorId = Auth::id();
        
        // Ambil conversation yang melibatkan instruktur ini dengan pesan terbaru
        $conversations = Conversation::whereHas('participants', function ($query) use ($instructorId) {
            $query->where('user_id', $instructorId);
        })
        ->with(['latestMessage.user', 'participants'])
        ->whereHas('messages')
        ->orderBy('updated_at', 'desc')
        ->take(10)
        ->get();
        
        return $conversations->map(function ($conversation) use ($instructorId) {
            $latestMessage = $conversation->latestMessage;
            $otherParticipant = $conversation->participants->where('id', '!=', $instructorId)->first();
            
            if (!$latestMessage) {
                return null;
            }
            
            $createdAt = Carbon::parse($latestMessage->created_at);
            
            return (object)[
                'message_id' => $latestMessage->id,
                'conversation_id' => $conversation->id,
                'sender_name' => $latestMessage->user->name ?? 'N/A',
                'sender_avatar' => $latestMessage->user->profile && $latestMessage->user->profile->photo_path 
                    ? asset('storage/' . $latestMessage->user->profile->photo_path)
                    : asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-1.jpg'),
                'other_participant_name' => $otherParticipant->name ?? 'N/A',
                'conversation_title' => $conversation->title ?? 'Chat',
                'message_preview' => substr($latestMessage->body ?? '', 0, 50) . (strlen($latestMessage->body ?? '') > 50 ? '...' : ''),
                'message_subject' => 'Pesan',
                'message_date' => $createdAt->format('d M Y'),
                'message_time' => $createdAt->format('H:i'),
            ];
        })->filter()->toArray();
    }

    private function getUnreadMessagesCount()
    {
        $instructorId = Auth::id();
        
        // Hitung pesan yang bukan dari instruktur ini (pesan masuk)
        return Message::whereHas('conversation', function ($query) use ($instructorId) {
            $query->whereHas('participants', function ($q) use ($instructorId) {
                $q->where('user_id', $instructorId);
            });
        })
        ->where('user_id', '!=', $instructorId)
        ->count();
    }

    // ==================== QUICK ACTIONS METHODS ====================
    
    /**
     * Menampilkan form untuk membuat sesi baru
     */
    public function createSession()
    {
        $instructorId = Auth::id();
        
        // Ambil kursus yang sudah pernah diajar oleh instruktur ini
        $courses = Course::whereHas('sessions', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->get();
        
        // Jika tidak ada, ambil semua kursus (untuk fleksibilitas - instruktur baru)
        if ($courses->isEmpty()) {
            $courses = Course::all();
        }
        
        return view('instructor.dashboard.create-session', compact('courses'));
    }

    /**
     * Menyimpan sesi baru
     */
    public function storeSession(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'mode' => 'required|in:online,offline,hybrid',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:30|max:480',
            'meeting_url' => 'nullable|url|required_if:mode,online,hybrid',
            'meeting_platform' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255|required_if:mode,offline,hybrid',
        ]);

        $session = CourseSession::create([
            'course_id' => $request->course_id,
            'instructor_id' => Auth::id(),
            'title' => $request->title,
            'mode' => $request->mode,
            'scheduled_at' => $request->scheduled_at,
            'duration_minutes' => $request->duration_minutes,
            'meeting_url' => $request->meeting_url,
            'meeting_platform' => $request->meeting_platform,
            'location' => $request->location,
        ]);

        return redirect()->route('instructor.sessions')
            ->with('success', 'Sesi berhasil dibuat!');
    }

    /**
     * Menampilkan form untuk input absensi
     */
    public function inputAttendance($sessionId)
    {
        $session = CourseSession::with(['course', 'attendances.student'])
            ->findOrFail($sessionId);
        
        // Pastikan sesi ini milik instruktur yang login
        if ($session->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        // Ambil semua peserta yang terdaftar di kursus ini
        $enrollments = Enrollment::where('course_id', $session->course_id)
            ->where('status', EnrollmentStatus::Active)
            ->with('user')
            ->get();
        
        // Cek apakah semua peserta sudah punya absensi
        $attendanceUserIds = $session->attendances->pluck('user_id')->unique();
        $enrollmentUserIds = $enrollments->pluck('user_id')->unique();
        
        // Jika semua peserta sudah punya absensi, redirect dengan pesan
        $missingAttendance = $enrollmentUserIds->diff($attendanceUserIds);
        if ($missingAttendance->isEmpty() && $enrollments->count() > 0) {
            return redirect()->route('instructor.attendance')
                ->with('info', 'Absensi untuk sesi ini sudah lengkap.');
        }
        
        // Cek apakah sesi sudah selesai (waktu akhir sesi sudah lewat)
        $now = Carbon::now();
        $sessionEnd = Carbon::parse($session->scheduled_at)
            ->addMinutes($session->duration_minutes ?? 90);
        
        // Jika sesi belum selesai, tetap izinkan input (fleksibel untuk instruktur)
        // Tapi bisa ditambahkan warning atau info
        
        // Gabungkan dengan data absensi yang sudah ada
        $students = $enrollments->map(function ($enrollment) use ($session) {
            $attendance = $session->attendances->firstWhere('user_id', $enrollment->user_id);
            
            return (object)[
                'user_id' => $enrollment->user_id,
                'name' => $enrollment->user->name ?? 'N/A',
                'email' => $enrollment->user->email ?? 'N/A',
                'attendance_id' => $attendance->id ?? null,
                'status' => $attendance ? $attendance->status->value : null,
                'notes' => $attendance->notes ?? null,
            ];
        });
        
        return view('instructor.dashboard.input-attendance', compact('session', 'students'));
    }

    /**
     * Menyimpan absensi
     */
    public function storeAttendance(Request $request, $sessionId)
    {
        $session = CourseSession::findOrFail($sessionId);
        
        // Pastikan sesi ini milik instruktur yang login
        if ($session->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.user_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:present,absent,excused',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($request, $session) {
                foreach ($request->attendances as $attendanceData) {
                    // Cari atau buat attendance record
                    $attendance = Attendance::firstOrNew(
                        [
                            'course_session_id' => $session->id,
                            'user_id' => $attendanceData['user_id'],
                        ]
                    );
                    
                    // Update nilai status dan notes
                    $attendance->status = AttendanceStatus::from($attendanceData['status']);
                    $attendance->notes = $attendanceData['notes'] ?? null;
                    
                    // Simpan (akan create jika baru, update jika sudah ada)
                    $attendance->save();
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan absensi: ' . $e->getMessage()]);
        }

        return redirect()->route('instructor.attendance')
            ->with('success', 'Absensi berhasil disimpan!');
    }

    /**
     * Approve reschedule request
     */
    public function approveReschedule($requestId)
    {
        $request = RescheduleRequest::with('session')->findOrFail($requestId);
        
        // Pastikan sesi ini milik instruktur yang login
        if ($request->session->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        DB::transaction(function () use ($request) {
            // Update status reschedule request
            $request->update([
                'status' => RescheduleStatus::Approved,
                'decided_by' => Auth::id(),
                'decided_at' => now(),
            ]);
            
            // Update scheduled_at session dengan proposed_at
            $request->session->update([
                'scheduled_at' => $request->proposed_at,
            ]);
        });

        return redirect()->route('instructor.reschedule')
            ->with('success', 'Reschedule request berhasil disetujui!');
    }

    /**
     * Reject reschedule request
     */
    public function rejectReschedule(Request $request, $requestId)
    {
        $rescheduleRequest = RescheduleRequest::with('session')->findOrFail($requestId);
        
        // Pastikan sesi ini milik instruktur yang login
        if ($rescheduleRequest->session->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $rescheduleRequest->update([
            'status' => RescheduleStatus::Rejected,
            'decided_by' => Auth::id(),
            'decided_at' => now(),
            'decision_notes' => $request->decision_notes ?? null,
        ]);

        return redirect()->route('instructor.reschedule')
            ->with('success', 'Reschedule request berhasil ditolak!');
    }

    /**
     * Menampilkan halaman Quick Actions
     */
    public function quickActions()
    {
        return view('instructor.quick-actions');
    }

    // ==================== MATERI KURSUS METHODS ====================
    
    /**
     * Menampilkan daftar materi untuk kursus tertentu
     */
    public function materials($courseSlug)
    {
        $instructorId = Auth::id();
        
        $course = Course::where('slug', $courseSlug)
            ->whereHas('sessions', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->firstOrFail();
        
        $materials = CourseMaterial::where('course_id', $course->id)
            ->orderBy('order')
            ->get();
        
        return view('instructor.dashboard.materials', compact('course', 'materials'));
    }

    /**
     * Menampilkan form untuk membuat materi baru
     */
    public function createMaterial($courseSlug)
    {
        $instructorId = Auth::id();
        
        $course = Course::where('slug', $courseSlug)
            ->whereHas('sessions', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->firstOrFail();
        
        return view('instructor.dashboard.create-material', compact('course'));
    }

    /**
     * Menyimpan materi baru
     */
    public function storeMaterial(Request $request, $courseSlug)
    {
        $instructorId = Auth::id();
        
        $course = Course::where('slug', $courseSlug)
            ->whereHas('sessions', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->firstOrFail();
        
        $validated = $request->validate([
            'type' => 'required|in:video,document',
            'title' => 'required|string|max:255',
            'scope' => 'required|in:online,offline,both',
            'order' => 'nullable|integer|min:0',
            'path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240',
            'url' => 'nullable|url|max:500',
        ]);

        // Validasi: harus ada path atau url
        if (!$request->hasFile('path') && !$request->filled('url')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['path' => 'File atau URL harus diisi salah satu']);
        }

        // Handle file upload
        if ($request->hasFile('path')) {
            $validated['path'] = $request->file('path')->store('materials', 'public');
            $validated['url'] = null;
        } else {
            $validated['path'] = null;
        }

        $validated['course_id'] = $course->id;
        $validated['type'] = MaterialType::from($validated['type']);

        CourseMaterial::create($validated);

        return redirect()->route('instructor.materials', $course->slug)
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk edit materi
     */
    public function editMaterial($courseSlug, CourseMaterial $material)
    {
        $instructorId = Auth::id();
        
        $course = Course::where('slug', $courseSlug)
            ->whereHas('sessions', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->firstOrFail();
        
        if ($material->course_id !== $course->id) {
            abort(404);
        }
        
        return view('instructor.dashboard.edit-material', compact('course', 'material'));
    }

    /**
     * Update materi
     */
    public function updateMaterial(Request $request, $courseSlug, CourseMaterial $material)
    {
        $instructorId = Auth::id();
        
        $course = Course::where('slug', $courseSlug)
            ->whereHas('sessions', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->firstOrFail();
        
        if ($material->course_id !== $course->id) {
            abort(404);
        }

        $validated = $request->validate([
            'type' => 'required|in:video,document',
            'title' => 'required|string|max:255',
            'scope' => 'required|in:online,offline,both',
            'order' => 'nullable|integer|min:0',
            'path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240',
            'url' => 'nullable|url|max:500',
        ]);

        // Validasi: harus ada path atau url jika tidak ada yang sudah ada
        if (!$request->hasFile('path') && !$request->filled('url') && !$material->path && !$material->url) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['path' => 'File atau URL harus diisi salah satu']);
        }

        // Handle file upload
        if ($request->hasFile('path')) {
            // Delete old file if exists
            if ($material->path && Storage::disk('public')->exists($material->path)) {
                Storage::disk('public')->delete($material->path);
            }
            $validated['path'] = $request->file('path')->store('materials', 'public');
            $validated['url'] = null;
        } elseif ($request->filled('url')) {
            // If new URL is provided and old file exists, delete it
            if ($material->path && Storage::disk('public')->exists($material->path)) {
                Storage::disk('public')->delete($material->path);
            }
            $validated['path'] = null;
        }

        $validated['type'] = MaterialType::from($validated['type']);

        $material->update($validated);

        return redirect()->route('instructor.materials', $course->slug)
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Hapus materi
     */
    public function destroyMaterial($courseSlug, CourseMaterial $material)
    {
        $instructorId = Auth::id();
        
        $course = Course::where('slug', $courseSlug)
            ->whereHas('sessions', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->firstOrFail();
        
        if ($material->course_id !== $course->id) {
            abort(404);
        }

        // Delete file if exists
        if ($material->path && Storage::disk('public')->exists($material->path)) {
            Storage::disk('public')->delete($material->path);
        }

        $material->delete();

        return redirect()->route('instructor.materials', $course->slug)
            ->with('success', 'Materi berhasil dihapus!');
    }

    // ==================== PESERTA KURSUS METHODS ====================
    
    /**
     * Menampilkan daftar peserta untuk kursus tertentu
     */
    public function students($courseSlug)
    {
        $instructorId = Auth::id();
        
        $course = Course::where('slug', $courseSlug)
            ->whereHas('sessions', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->firstOrFail();
        
        $enrollments = Enrollment::where('course_id', $course->id)
            ->with(['user', 'certificate'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('instructor.dashboard.students', compact('course', 'enrollments'));
    }

    // ==================== RIWAYAT TRANSAKSI METHODS ====================
    
    /**
     * Menampilkan riwayat transaksi untuk kursus instruktur
     */
    public function transactions()
    {
        $instructorId = Auth::id();
        
        // Ambil semua kursus yang diajar oleh instruktur ini
        $courseIds = Course::whereHas('sessions', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->pluck('id');
        
        // Ambil semua payment dari enrollment kursus-kursus tersebut
        $payments = Payment::whereHas('enrollment', function ($query) use ($courseIds) {
            $query->whereIn('course_id', $courseIds);
        })
        ->with(['enrollment.course', 'enrollment.user'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('instructor.dashboard.transactions', compact('payments'));
    }

    // ==================== SERTIFIKAT METHODS ====================
    
    /**
     * Menampilkan daftar sertifikat untuk kursus instruktur
     */
    public function certificates()
    {
        $instructorId = Auth::id();
        
        // Ambil semua kursus yang diajar oleh instruktur ini
        $courseIds = Course::whereHas('sessions', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->pluck('id');
        
        // Ambil semua sertifikat dari enrollment kursus-kursus tersebut
        $certificates = Certificate::whereHas('enrollment', function ($query) use ($courseIds) {
            $query->whereIn('course_id', $courseIds);
        })
        ->with(['enrollment.course', 'enrollment.user'])
        ->orderBy('issued_at', 'desc')
        ->get();
        
        return view('instructor.dashboard.certificates', compact('certificates'));
    }

    /**
     * Generate sertifikat untuk enrollment tertentu
     */
    public function generateCertificate($enrollmentId)
    {
        $instructorId = Auth::id();
        
        $enrollment = Enrollment::with('course')->findOrFail($enrollmentId);
        
        // Pastikan kursus ini diajar oleh instruktur ini
        $hasSession = CourseSession::where('course_id', $enrollment->course_id)
            ->where('instructor_id', $instructorId)
            ->exists();
        
        if (!$hasSession) {
            abort(403, 'Unauthorized');
        }
        
        // Cek apakah sudah ada sertifikat
        if ($enrollment->certificate) {
            return redirect()->route('instructor.certificates')
                ->with('error', 'Sertifikat untuk peserta ini sudah ada!');
        }
        
        // Generate sertifikat
        Certificate::issueFor($enrollment);
        
        return redirect()->route('instructor.certificates')
            ->with('success', 'Sertifikat berhasil dibuat!');
    }

    /**
     * Menampilkan halaman profil
     */
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        return view('instructor.profile.index', compact('user', 'profile'));
    }

    /**
     * Update profil instructor
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
        
        $profile->save();
        
        return redirect()->route('instructor.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
