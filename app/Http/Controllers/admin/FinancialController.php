<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use App\Enums\PaymentStatus;
use App\Enums\EnrollmentStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialController extends Controller
{
    /**
     * Display financial dashboard
     */
    public function index()
    {
        // Get income data for last 12 months
        $incomeData = $this->getIncomeChartData();
        
        // Get KPI data
        $kpiData = $this->getKPIData();
        
        return view('admin.financial.index', compact('incomeData', 'kpiData'));
    }

    /**
     * Get income chart data for last 12 months
     */
    private function getIncomeChartData()
    {
        $months = [];
        $income = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $total = Payment::where('status', PaymentStatus::Paid)
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->sum('amount');
            
            $months[] = $date->format('M Y');
            $income[] = $total;
        }
        
        return [
            'months' => $months,
            'income' => $income,
        ];
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
        
        // Total Pendapatan Bulan Ini
        $currentMonthIncome = Payment::where('status', PaymentStatus::Paid)
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->sum('amount');
        
        // Total Pendapatan Bulan Lalu
        $lastMonthIncome = Payment::where('status', PaymentStatus::Paid)
            ->whereBetween('paid_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');
        
        // Calculate percentage change
        $incomeChange = 0;
        if ($lastMonthIncome > 0) {
            $incomeChange = (($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100;
        } elseif ($currentMonthIncome > 0) {
            $incomeChange = 100;
        }
        
        // Pembayaran Pending
        $pendingPayments = Payment::where('status', PaymentStatus::Pending)->count();
        $pendingPaymentsTotal = Payment::where('status', PaymentStatus::Pending)->sum('amount');
        
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
    }
}

