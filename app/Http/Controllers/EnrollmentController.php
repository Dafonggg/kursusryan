<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\UserProfile;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\EnrollmentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class EnrollmentController extends Controller
{
    /**
     * Show checkout page
     */
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout!');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $courses = [];
        $total = 0;

        foreach ($cart as $courseId => $quantity) {
            $course = Course::find($courseId);
            if ($course) {
                $courses[] = $course;
                $total += $course->price;
            }
        }

        return view('landing.checkout', compact('courses', 'total'));
    }

    /**
     * Process enrollment and payment
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        try {
            $request->validate([
                'payment_method' => 'required|in:qris,transfer',
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'bank_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:255',
                'account_name' => 'nullable|string|max:255',
            ], [
                'payment_method.required' => 'Pilih metode pembayaran terlebih dahulu.',
                'payment_method.in' => 'Metode pembayaran tidak valid.',
                'payment_proof.required' => 'Upload bukti pembayaran wajib diisi.',
                'payment_proof.image' => 'File harus berupa gambar.',
                'payment_proof.mimes' => 'Format file harus JPG, JPEG, atau PNG.',
                'payment_proof.max' => 'Ukuran file maksimal 2MB.',
            ]);

            $cart = Session::get('cart', []);
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
            }

            $user = Auth::user();

            // Store payment proof once
            if (!$request->hasFile('payment_proof')) {
                return redirect()->back()->withErrors(['payment_proof' => 'Bukti pembayaran wajib diupload.'])->withInput();
            }

            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Create enrollments and payments for each course
            $enrollmentsCreated = 0;
            $alreadyEnrolledCourses = [];
            $updatedCart = [];

            foreach ($cart as $courseId => $quantity) {
                $course = Course::find($courseId);
                if (!$course) continue;

                // Check if user already enrolled (only check active or pending status)
                $existingEnrollment = Enrollment::where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Pending])
                    ->first();

                if ($existingEnrollment) {
                    // Track already enrolled courses
                    $alreadyEnrolledCourses[] = $course->title;
                    continue; // Skip if already enrolled
                }

                // Keep course in cart if not enrolled
                $updatedCart[$courseId] = $quantity;

                // Create enrollment
                $enrollment = Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'status' => EnrollmentStatus::Pending,
                ]);

                // Create payment
                $payment = Payment::create([
                    'enrollment_id' => $enrollment->id,
                    'amount' => $course->price,
                    'method' => PaymentMethod::from($request->payment_method),
                    'status' => PaymentStatus::Pending,
                    'meta' => [
                        'proof' => $paymentProofPath,
                        'proof_image' => $paymentProofPath,
                        'bukti' => $paymentProofPath,
                        'payment_proof_path' => $paymentProofPath,
                        'bank' => $request->bank_name ?? null,
                        'bank_name' => $request->bank_name ?? null,
                        'account_number' => $request->account_number ?? null,
                        'no_rekening' => $request->account_number ?? null,
                        'account_name' => $request->account_name ?? null,
                    ],
                ]);

                $enrollmentsCreated++;
            }

            // Update cart to remove already enrolled courses
            Session::put('cart', $updatedCart);

            if ($enrollmentsCreated === 0) {
                // Delete payment proof if no enrollments created
                Storage::disk('public')->delete($paymentProofPath);
                $errorMessage = 'Anda sudah terdaftar di semua kursus yang dipilih.';
                if (!empty($alreadyEnrolledCourses)) {
                    $errorMessage = 'Anda sudah terdaftar di kursus: ' . implode(', ', $alreadyEnrolledCourses);
                }
                return redirect()->route('cart.index')->with('error', $errorMessage);
            }

            // Show warning if some courses were already enrolled
            $warningMessage = '';
            if (!empty($alreadyEnrolledCourses)) {
                $warningMessage = 'Beberapa kursus sudah terdaftar dan dilewati: ' . implode(', ', $alreadyEnrolledCourses);
            }

            // Clear cart only if all courses in cart were processed
            // If all courses were successfully enrolled, clear the cart
            // If some courses were already enrolled, update cart with remaining courses
            if (count($cart) === $enrollmentsCreated) {
                // All courses were successfully enrolled, clear cart
                Session::forget('cart');
            } else {
                // Some courses were already enrolled, update cart with remaining courses
                Session::put('cart', $updatedCart);
            }

            $successMessage = 'Pembayaran berhasil dikirim! Silakan lengkapi data diri Anda.';
            if ($warningMessage) {
                return redirect()->route('enrollment.complete-data')
                    ->with('success', $successMessage)
                    ->with('warning', $warningMessage);
            }

            return redirect()->route('enrollment.complete-data')
                ->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Enrollment store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Show complete data form
     */
    public function completeData()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $profile = $user->profile;

        // Get pending enrollments
        $pendingEnrollments = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatus::Pending)
            ->with(['course', 'payments'])
            ->get();

        if ($pendingEnrollments->isEmpty()) {
            return redirect()->route('student.my-courses')
                ->with('info', 'Tidak ada pendaftaran yang perlu dilengkapi.');
        }

        return view('landing.complete-data', compact('profile', 'pendingEnrollments'));
    }

    /**
     * Store complete data
     */
    public function storeCompleteData(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'kk' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            $profile = new UserProfile();
            $profile->user_id = $user->id;
        }

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

        return redirect()->route('student.my-courses')
            ->with('success', 'Data diri berhasil dilengkapi! Menunggu verifikasi admin.');
    }
}
