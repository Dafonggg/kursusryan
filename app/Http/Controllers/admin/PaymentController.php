<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
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
            return redirect()->route('admin.payments.pending')
                ->with('success', 'Pembayaran berhasil diverifikasi!');
        } else {
            $payment->update([
                'status' => PaymentStatus::Failed,
            ]);
            return redirect()->route('admin.payments.pending')
                ->with('success', 'Pembayaran ditolak!');
        }
    }
}

