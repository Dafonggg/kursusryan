<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\User;
use App\Enums\EnrollmentStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display student analytics dashboard
     */
    public function index()
    {
        // Statistik per kursus
        $courseStats = $this->getCourseStatistics();
        
        // Tingkat kelulusan (completion rate)
        $completionStats = $this->getCompletionStatistics();
        
        // Statistik sertifikat
        $certificateStats = $this->getCertificateStatistics();
        
        // Statistik peserta per kursus
        $studentPerCourse = $this->getStudentsPerCourse();
        
        return view('admin.analytics.index', compact(
            'courseStats',
            'completionStats',
            'certificateStats',
            'studentPerCourse'
        ));
    }

    /**
     * Get statistics for each course
     */
    private function getCourseStatistics()
    {
        return Course::withCount(['enrollments', 'sessions'])
            ->with([
                'enrollments' => function ($query) {
                    $query->where('status', EnrollmentStatus::Active);
                }
            ])
            ->get()
            ->map(function ($course) {
                return [
                    'course' => $course,
                    'total_enrollments' => $course->enrollments_count,
                    'active_enrollments' => $course->enrollments->count(),
                    'completed_enrollments' => Enrollment::where('course_id', $course->id)
                        ->where('status', EnrollmentStatus::Completed)
                        ->count(),
                    'total_sessions' => $course->sessions_count,
                    'certificates_issued' => Certificate::whereHas('enrollment', function ($q) use ($course) {
                        $q->where('course_id', $course->id);
                    })->count(),
                ];
            });
    }

    /**
     * Get completion statistics
     */
    private function getCompletionStatistics()
    {
        $totalEnrollments = Enrollment::count();
        $activeEnrollments = Enrollment::where('status', EnrollmentStatus::Active)->count();
        $completedEnrollments = Enrollment::where('status', EnrollmentStatus::Completed)->count();
        $expiredEnrollments = Enrollment::where('status', EnrollmentStatus::Expired)->count();
        
        $completionRate = $totalEnrollments > 0 
            ? round(($completedEnrollments / $totalEnrollments) * 100, 2) 
            : 0;

        return [
            'total' => $totalEnrollments,
            'active' => $activeEnrollments,
            'completed' => $completedEnrollments,
            'expired' => $expiredEnrollments,
            'completion_rate' => $completionRate,
        ];
    }

    /**
     * Get certificate statistics
     */
    private function getCertificateStatistics()
    {
        $totalCertificates = Certificate::count();
        $thisMonth = Certificate::whereMonth('issued_at', Carbon::now()->month)
            ->whereYear('issued_at', Carbon::now()->year)
            ->count();
        $thisYear = Certificate::whereYear('issued_at', Carbon::now()->year)->count();

        // Certificates by course
        $certificatesByCourse = Course::withCount(['enrollments' => function ($query) {
            $query->whereHas('certificate');
        }])->get();

        return [
            'total' => $totalCertificates,
            'this_month' => $thisMonth,
            'this_year' => $thisYear,
            'by_course' => $certificatesByCourse,
        ];
    }

    /**
     * Get students per course statistics
     */
    private function getStudentsPerCourse()
    {
        return Course::withCount([
            'enrollments as total_students',
            'enrollments as active_students' => function ($query) {
                $query->where('status', EnrollmentStatus::Active);
            },
            'enrollments as completed_students' => function ($query) {
                $query->where('status', EnrollmentStatus::Completed);
            },
        ])
        ->get()
        ->map(function ($course) {
            return [
                'course_name' => $course->title,
                'total' => $course->total_students,
                'active' => $course->active_students,
                'completed' => $course->completed_students,
                'completion_rate' => $course->total_students > 0 
                    ? round(($course->completed_students / $course->total_students) * 100, 2) 
                    : 0,
            ];
        })
        ->sortByDesc('total');
    }

    /**
     * Get detailed analytics for a specific course
     */
    public function courseDetail(Course $course)
    {
        $enrollments = Enrollment::where('course_id', $course->id)
            ->with(['user.profile', 'payments', 'certificate'])
            ->latest()
            ->get();

        $stats = [
            'total' => $enrollments->count(),
            'active' => $enrollments->where('status', EnrollmentStatus::Active)->count(),
            'completed' => $enrollments->where('status', EnrollmentStatus::Completed)->count(),
            'with_certificate' => $enrollments->filter(function ($enrollment) {
                return $enrollment->certificate !== null;
            })->count(),
        ];

        return view('admin.analytics.course-detail', compact('course', 'enrollments', 'stats'));
    }
}

