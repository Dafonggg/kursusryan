<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentStatusController extends Controller
{
    public function index()
    {
        // Data dummy untuk Payment Status
        // TODO: Ganti dengan query database yang sebenarnya
        $payment_status = (object)[
            'payment_id' => 1,
            'payment_amount' => 'Rp 2.500.000',
            'payment_status' => 'Paid',
            'payment_status_badge' => 'success',
            'payment_date' => Carbon::now()->subDays(5)->format('d M Y'),
            'payment_method' => 'Bank Transfer',
            'invoice_url' => '#',
        ];

        return view('student.dashboard.components.payment-status', compact('payment_status'));
    }
}

