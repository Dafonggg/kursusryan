<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Enums\EnrollmentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $courses = [];
        $total = 0;
        $updatedCart = [];
        $removedCourses = [];

        foreach ($cart as $courseId => $quantity) {
            $course = Course::find($courseId);
            if (!$course) continue;

            // Check if user is already enrolled (only check active or pending status)
            if (Auth::check()) {
                $existingEnrollment = Enrollment::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Pending])
                    ->first();

                if ($existingEnrollment) {
                    // Remove from cart if already enrolled
                    $removedCourses[] = $course->title;
                    continue;
                }
            }

            // Keep course in cart if not enrolled
            $updatedCart[$courseId] = $quantity;
            $courses[] = [
                'id' => $course->id,
                'slug' => $course->slug,
                'title' => $course->title,
                'price' => $course->price,
                'image' => $course->image,
                'quantity' => $quantity,
                'subtotal' => $course->price * $quantity,
            ];
            $total += $course->price * $quantity;
        }

        // Update cart to remove already enrolled courses
        if (!empty($removedCourses)) {
            Session::put('cart', $updatedCart);
        }

        return view('landing.cart', compact('courses', 'total', 'removedCourses'));
    }

    /**
     * Add course to cart
     */
    public function add(Request $request, $courseId)
    {
        // Mencegah admin dan instructor untuk menambahkan kursus ke keranjang
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->back()->with('error', 'Anda admin, tidak dapat menambahkan kursus ke keranjang.');
            }
            if ($user->role === 'instructor') {
                return redirect()->back()->with('error', 'Anda instructor, tidak dapat menambahkan kursus ke keranjang.');
            }
        }

        $course = Course::find($courseId);
        
        if (!$course) {
            return redirect()->back()->with('error', 'Kursus tidak ditemukan!');
        }

        $cart = Session::get('cart', []);
        
        // Check if course already in cart
        if (isset($cart[$course->id])) {
            return redirect()->back()->with('error', 'Kursus sudah ada di keranjang!');
        }

        // Check if user is already enrolled (only check active or pending status)
        if (Auth::check()) {
            $existingEnrollment = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->whereIn('status', [EnrollmentStatus::Active, EnrollmentStatus::Pending])
                ->first();

            if ($existingEnrollment) {
                return redirect()->back()->with('error', 'Anda sudah terdaftar di kursus ini!');
            }
        }

        $cart[$course->id] = 1;
        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Kursus berhasil ditambahkan ke keranjang!');
    }

    /**
     * Remove course from cart
     */
    public function remove(Request $request, $courseId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$courseId]);
        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Kursus berhasil dihapus dari keranjang!');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan!');
    }

    /**
     * Get cart count (for AJAX)
     */
    public function count()
    {
        $cart = Session::get('cart', []);
        return response()->json(['count' => count($cart)]);
    }
}
