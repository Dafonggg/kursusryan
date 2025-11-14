<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        foreach ($cart as $courseId => $quantity) {
            $course = Course::find($courseId);
            if ($course) {
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
        }

        return view('landing.cart', compact('courses', 'total'));
    }

    /**
     * Add course to cart
     */
    public function add(Request $request, $courseId)
    {
        $course = Course::find($courseId);
        
        if (!$course) {
            return redirect()->back()->with('error', 'Kursus tidak ditemukan!');
        }

        $cart = Session::get('cart', []);
        
        // Check if course already in cart
        if (isset($cart[$course->id])) {
            return redirect()->back()->with('error', 'Kursus sudah ada di keranjang!');
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
