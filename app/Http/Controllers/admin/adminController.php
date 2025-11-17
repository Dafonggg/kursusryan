<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\CourseSession;
use App\Models\Enrollment;
use App\Models\RescheduleRequest;
use App\Models\Course;
use App\Models\User;
use App\Models\UserProfile;
use App\Enums\EnrollmentStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Display admin dashboardx
     */
    public function index()
    {
        // Initialize todaySessions dengan empty collection jika terjadi error
        $todaySessions = collect([]);
        
        try {
            // Get today's sessions
            $todaySessions = CourseSession::with(['course', 'instructor.profile', 'attendances'])
                ->whereDate('scheduled_at', Carbon::today())
                ->orderBy('scheduled_at')
                ->get()
                ->map(function ($session) {
                    // Calculate participant count from active enrollments
                    $participantCount = Enrollment::where('course_id', $session->course_id)
                        ->where('status', EnrollmentStatus::Active)
                        ->count();
                    
                    // Determine session status
                    $now = Carbon::now();
                    $scheduledAt = $session->scheduled_at;
                    
                    if (!$scheduledAt) {
                        return null;
                    }
                    
                    $endTime = $scheduledAt->copy()->addMinutes($session->duration_minutes);
                    
                    $status = 'Upcoming';
                    $statusBadge = 'warning';
                    
                    if ($now->between($scheduledAt, $endTime)) {
                        $status = 'Ongoing';
                        $statusBadge = 'success';
                    } elseif ($now->greaterThan($endTime)) {
                        $status = 'Completed';
                        $statusBadge = 'info';
                    }
                    
                    return (object)[
                        'session' => $session,
                        'participant_count' => $participantCount,
                        'status' => $status,
                        'status_badge' => $statusBadge,
                    ];
                })
                ->filter(); // Remove null values
        } catch (\Exception $e) {
            // Log error jika perlu
            \Log::error('Error fetching today sessions: ' . $e->getMessage());
        }

        // Get pending reschedule requests for dashboard
        $pendingReschedules = collect([]);
        try {
            $pendingReschedules = RescheduleRequest::with(['session.course', 'requester'])
                ->pending()
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error fetching pending reschedules: ' . $e->getMessage());
        }

        // Get KPI data for dashboard
        $kpiData = $this->getKPIData();

        // Get income chart data for dashboard
        $incomeData = $this->getIncomeChartData();

        // Get latest registrations for dashboard
        $latestRegistrations = collect([]);
        try {
            $latestRegistrations = Enrollment::with(['user.profile', 'course'])
                ->latest()
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error fetching latest registrations: ' . $e->getMessage());
        }

        return view('admin.dashboard.index', compact('todaySessions', 'pendingReschedules', 'kpiData', 'incomeData', 'latestRegistrations'));
    }

    /**
     * Get KPI summary data
     */
    private function getKPIData()
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();
        $sevenDaysAgo = $now->copy()->subDays(7);
        
        try {
            // Total Pendapatan Bulan Ini
            $currentMonthIncome = Payment::where('status', PaymentStatus::Paid)
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->sum('amount') ?? 0;
            
            // Total Pendapatan Bulan Lalu
            $lastMonthIncome = Payment::where('status', PaymentStatus::Paid)
                ->whereBetween('paid_at', [$lastMonthStart, $lastMonthEnd])
                ->sum('amount') ?? 0;
            
            // Calculate percentage change
            $incomeChange = 0;
            if ($lastMonthIncome > 0) {
                $incomeChange = (($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100;
            } elseif ($currentMonthIncome > 0) {
                $incomeChange = 100;
            }
            
            // Pembayaran Pending
            $pendingPayments = Payment::where('status', PaymentStatus::Pending)->count();
            $pendingPaymentsTotal = Payment::where('status', PaymentStatus::Pending)->sum('amount') ?? 0;
            
            // Enrol Aktif
            $activeEnrollments = Enrollment::where('status', EnrollmentStatus::Active)->count();
            
            // Enrol Kadaluarsa Minggu Ini
            $expiringThisWeek = Enrollment::where('status', EnrollmentStatus::Active)
                ->whereBetween('expires_at', [$weekStart, $weekEnd])
                ->count();
            
            // Kursus Aktif
            $activeCourses = Course::count();
            
            // Instruktur Aktif
            $activeInstructors = User::where('role', 'instructor')->count();
            
            // User Baru (7 hari terakhir)
            $newUsers = User::where('created_at', '>=', $sevenDaysAgo)->count();
            
            return [
                'current_month_income' => $currentMonthIncome,
                'income_change' => round($incomeChange, 1),
                'pending_payments_count' => $pendingPayments,
                'pending_payments_total' => $pendingPaymentsTotal,
                'active_enrollments' => $activeEnrollments,
                'expiring_this_week' => $expiringThisWeek,
                'active_courses' => $activeCourses,
                'active_instructors' => $activeInstructors,
                'new_users' => $newUsers,
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching KPI data: ' . $e->getMessage());
            return [
                'current_month_income' => 0,
                'income_change' => 0,
                'pending_payments_count' => 0,
                'pending_payments_total' => 0,
                'active_enrollments' => 0,
                'expiring_this_week' => 0,
                'active_courses' => 0,
                'active_instructors' => 0,
                'new_users' => 0,
            ];
        }
    }

    /**
     * Get income chart data for last 12 months
     */
    private function getIncomeChartData()
    {
        try {
            $months = [];
            $income = [];
            
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                
                $total = Payment::where('status', PaymentStatus::Paid)
                    ->whereBetween('paid_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                
                $months[] = $date->format('M Y');
                $income[] = $total;
            }
            
            return [
                'months' => $months,
                'income' => $income,
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching income chart data: ' . $e->getMessage());
            return [
                'months' => [],
                'income' => [],
            ];
        }
    }

    /**
     * Display quick actions page
     */
    public function quickActions()
    {
        return view('admin.quick-actions');
    }

    /**
     * Export financial data to CSV
     */
    public function exportFinancialData()
    {
        $payments = Payment::with(['enrollment.course', 'enrollment.user'])
            ->latest('created_at')
            ->get();

        $filename = 'financial_data_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // BOM untuk UTF-8 agar Excel bisa membaca karakter Indonesia
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV
            fputcsv($file, [
                'ID Pembayaran',
                'Tanggal',
                'Nama Siswa',
                'Email',
                'Kursus',
                'Jumlah',
                'Metode',
                'Status',
                'Tanggal Bayar',
                'Referensi'
            ]);

            // Data rows
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->enrollment->user->name ?? '-',
                    $payment->enrollment->user->email ?? '-',
                    $payment->enrollment->course->title ?? '-',
                    number_format($payment->amount, 0, ',', '.'),
                    $payment->method->value ?? '-',
                    $payment->status->value ?? '-',
                    $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : '-',
                    $payment->reference ?? '-',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export financial data to PDF
     */
    public function exportFinancialDataPDF()
    {
        $payments = Payment::with(['enrollment.course', 'enrollment.user'])
            ->latest('created_at')
            ->get();

        // Calculate summary statistics
        $totalIncome = $payments->where('status', PaymentStatus::Paid)->sum('amount');
        $totalPending = $payments->where('status', PaymentStatus::Pending)->sum('amount');
        $totalPaid = $payments->where('status', PaymentStatus::Paid)->count();
        $totalPendingCount = $payments->where('status', PaymentStatus::Pending)->count();

        $data = [
            'payments' => $payments,
            'total_income' => $totalIncome,
            'total_pending' => $totalPending,
            'total_paid' => $totalPaid,
            'total_pending_count' => $totalPendingCount,
            'generated_at' => Carbon::now()->format('d F Y H:i:s'),
        ];

        $pdf = Pdf::loadView('admin.financial.export-pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        
        $filename = 'laporan_keuangan_' . date('Y-m-d_His') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Menampilkan halaman profil
     */
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        return view('admin.profile.index', compact('user', 'profile'));
    }

    /**
     * Update profil admin
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
        
        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
