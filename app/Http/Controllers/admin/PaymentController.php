<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display all payments
     */
    public function index(Request $request)
    {
        $query = Payment::with(['enrollment.course', 'enrollment.user']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', PaymentStatus::from($request->status));
        }
        
        // Search by user name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('enrollment.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $payments = $query->latest('created_at')->paginate(15);
        
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Display pending payments
     */
    public function pending()
    {
        $payments = Payment::with(['enrollment.course', 'enrollment.user'])
            ->where('status', PaymentStatus::Pending)
            ->latest()
            ->paginate(10);
        
        return view('admin.payments.pending', compact('payments'));
    }

    /**
     * Verify/Approve a payment
     */
    public function verify(Request $request, Payment $payment)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            $payment->markAsPaid();
            
            // Activate enrollment
            $enrollment = $payment->enrollment;
            if ($enrollment && $enrollment->status === \App\Enums\EnrollmentStatus::Pending) {
                $enrollment->activate();
            }
            
            return redirect()->route('admin.payments.pending')
                ->with('success', 'Pembayaran berhasil diverifikasi dan enrollment diaktifkan!');
        } else {
            $payment->update([
                'status' => PaymentStatus::Failed,
            ]);
            return redirect()->route('admin.payments.pending')
                ->with('success', 'Pembayaran ditolak!');
        }
    }
}

