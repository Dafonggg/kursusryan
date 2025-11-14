<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RescheduleRequest;
use App\Enums\RescheduleStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RescheduleController extends Controller
{
    /**
     * Display a listing of reschedule requests
     */
    public function index()
    {
        $rescheduleRequests = RescheduleRequest::with([
                'session.course', 
                'session.instructor', 
                'requester.profile'
            ])
            ->latest()
            ->paginate(10);
        
        return view('admin.reschedules.index', compact('rescheduleRequests'));
    }

    /**
     * Display pending reschedule requests
     */
    public function pending()
    {
        $rescheduleRequests = RescheduleRequest::with([
                'session.course', 
                'session.instructor', 
                'requester.profile'
            ])
            ->pending()
            ->latest()
            ->paginate(10);
        
        return view('admin.reschedules.pending', compact('rescheduleRequests'));
    }

    /**
     * Approve a reschedule request
     */
    public function approve(Request $request, RescheduleRequest $rescheduleRequest)
    {
        if ($rescheduleRequest->status !== RescheduleStatus::Pending) {
            return redirect()->back()
                ->with('error', 'Permintaan reschedule ini sudah diproses sebelumnya.');
        }

        // Update session scheduled_at to the proposed time
        if ($rescheduleRequest->proposed_at) {
            $rescheduleRequest->session->update([
                'scheduled_at' => $rescheduleRequest->proposed_at
            ]);
            
            // Update decided_by and decided_at
            $rescheduleRequest->update([
                'decided_by' => auth()->id(),
                'decided_at' => now(),
            ]);
        }

        $rescheduleRequest->approve();

        return redirect()->route('admin.reschedules.pending')
            ->with('success', 'Permintaan reschedule berhasil disetujui!');
    }

    /**
     * Reject a reschedule request
     */
    public function reject(Request $request, RescheduleRequest $rescheduleRequest)
    {
        if ($rescheduleRequest->status !== RescheduleStatus::Pending) {
            return redirect()->back()
                ->with('error', 'Permintaan reschedule ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'decision_notes' => 'nullable|string|max:500',
        ]);

        $rescheduleRequest->reject($validated['decision_notes'] ?? null);
        
        // Update decided_by and decided_at
        $rescheduleRequest->update([
            'decided_by' => auth()->id(),
            'decided_at' => now(),
            'decision_notes' => $validated['decision_notes'] ?? null,
        ]);

        return redirect()->route('admin.reschedules.pending')
            ->with('success', 'Permintaan reschedule ditolak!');
    }
}

