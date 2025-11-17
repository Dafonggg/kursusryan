<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\detailController;
use App\Http\Controllers\listingController;
use App\Http\Controllers\contactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseMaterialController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RescheduleController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Instuctor\InstructorController;
use App\Http\Controllers\User\StudentController;
use App\Http\Controllers\User\ActiveDaysCounterController;
use App\Http\Controllers\User\CertificateReadyController;
use App\Http\Controllers\User\ChatShortcutController;
use App\Http\Controllers\User\ContinueLearningController;
use App\Http\Controllers\User\NextSessionController;
use App\Http\Controllers\User\PaymentStatusController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing Page Routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/detail-kursus/{course:slug}', [detailController::class, 'detail'])->name('detail-kursus');
Route::get('/daftar-kursus', [listingController::class, 'listing'])->name('daftar-kursus');
Route::get('/contact', [contactController::class, 'contact'])->name('contact');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{courseId}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{courseId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Enrollment Routes - Hanya untuk student dan user
Route::get('/checkout', [EnrollmentController::class, 'checkout'])->name('checkout')->middleware(['auth', 'checkrole:student,user']);
Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store')->middleware(['auth', 'checkrole:student,user']);
Route::get('/enrollment/complete-data', [EnrollmentController::class, 'completeData'])->name('enrollment.complete-data')->middleware(['auth', 'checkrole:student,user']);
Route::post('/enrollment/complete-data', [EnrollmentController::class, 'storeCompleteData'])->name('enrollment.store-complete-data')->middleware(['auth', 'checkrole:student,user']);
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'loginPost'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'registerPost'])->name('register.post');

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');


// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::group(['middleware' => 'auth','checkrole:admin'], function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/quick-actions', [AdminController::class, 'quickActions'])->name('quick-actions');
        Route::get('/export-financial', [AdminController::class, 'exportFinancialData'])->name('export-financial');
        Route::get('/export-financial-pdf', [AdminController::class, 'exportFinancialDataPDF'])->name('export-financial-pdf');
        
        // Courses
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course:slug}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course:slug}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course:slug}', [CourseController::class, 'destroy'])->name('courses.destroy');
        
        // Course Materials
        Route::get('/courses/{course:slug}/materials', [CourseMaterialController::class, 'index'])->name('materials.index');
        Route::get('/courses/{course:slug}/materials/create', [CourseMaterialController::class, 'create'])->name('materials.create');
        Route::post('/courses/{course:slug}/materials', [CourseMaterialController::class, 'store'])->name('materials.store');
        Route::get('/courses/{course:slug}/materials/{material}/edit', [CourseMaterialController::class, 'edit'])->name('materials.edit');
        Route::put('/courses/{course:slug}/materials/{material}', [CourseMaterialController::class, 'update'])->name('materials.update');
        Route::delete('/courses/{course:slug}/materials/{material}', [CourseMaterialController::class, 'destroy'])->name('materials.destroy');
        
        // Sessions
        Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
        Route::get('/sessions/create', [SessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
        
        // Payments
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/pending', [PaymentController::class, 'pending'])->name('payments.pending');
        Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        
        // Reschedules
        Route::get('/reschedules', [RescheduleController::class, 'index'])->name('reschedules.index');
        Route::get('/reschedules/pending', [RescheduleController::class, 'pending'])->name('reschedules.pending');
        Route::post('/reschedules/{rescheduleRequest}/approve', [RescheduleController::class, 'approve'])->name('reschedules.approve');
        Route::post('/reschedules/{rescheduleRequest}/reject', [RescheduleController::class, 'reject'])->name('reschedules.reject');
        
        // Financial
        Route::get('/financial', [FinancialController::class, 'index'])->name('financial.index');
        
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');
        
        // Chat
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/chat/create', [ChatController::class, 'create'])->name('chat.create');
        Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
        Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/chat/{conversation}/message', [ChatController::class, 'sendMessage'])->name('chat.send-message');
        Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unread-count');
        
        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/course/{course:slug}', [AnalyticsController::class, 'courseDetail'])->name('analytics.course-detail');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/api', [SettingsController::class, 'getSettings'])->name('settings.api');
        
        // Profile Routes
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    });
});

// Instructor Routes
Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'checkrole:instructor'])->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'index'])->name('dashboard');
    Route::get('/quick-actions', [InstructorController::class, 'quickActions'])->name('quick-actions');
    Route::get('/sessions', [InstructorController::class, 'sessions'])->name('sessions');
    Route::get('/courses', [InstructorController::class, 'courses'])->name('courses');
    Route::get('/attendance', [InstructorController::class, 'attendance'])->name('attendance');
    Route::get('/reschedule', [InstructorController::class, 'reschedule'])->name('reschedule');
    Route::get('/messages', [InstructorController::class, 'messages'])->name('messages');
    Route::get('/transactions', [InstructorController::class, 'transactions'])->name('transactions');
    Route::get('/certificates', [InstructorController::class, 'certificates'])->name('certificates');
    
    // Chat Routes
    Route::get('/chat/{conversationId}', [InstructorController::class, 'showChat'])->name('chat.show');
    Route::post('/chat/create', [InstructorController::class, 'createOrGetConversation'])->name('chat.create');
    Route::post('/chat/{conversationId}/send', [InstructorController::class, 'sendMessage'])->name('chat.send');
    
    // Quick Actions Routes
    Route::get('/sessions/create', [InstructorController::class, 'createSession'])->name('sessions.create');
    Route::post('/sessions', [InstructorController::class, 'storeSession'])->name('sessions.store');
    Route::get('/attendance/{sessionId}/input', [InstructorController::class, 'inputAttendance'])->name('attendance.input');
    Route::post('/attendance/{sessionId}', [InstructorController::class, 'storeAttendance'])->name('attendance.store');
    Route::post('/reschedule/{requestId}/approve', [InstructorController::class, 'approveReschedule'])->name('reschedule.approve');
    Route::post('/reschedule/{requestId}/reject', [InstructorController::class, 'rejectReschedule'])->name('reschedule.reject');
    
    // Materi Kursus Routes
    Route::get('/courses/{courseSlug}/materials', [InstructorController::class, 'materials'])->name('materials');
    Route::get('/courses/{courseSlug}/materials/create', [InstructorController::class, 'createMaterial'])->name('materials.create');
    Route::post('/courses/{courseSlug}/materials', [InstructorController::class, 'storeMaterial'])->name('materials.store');
    Route::get('/courses/{courseSlug}/materials/{material}/edit', [InstructorController::class, 'editMaterial'])->name('materials.edit');
    Route::put('/courses/{courseSlug}/materials/{material}', [InstructorController::class, 'updateMaterial'])->name('materials.update');
    Route::delete('/courses/{courseSlug}/materials/{material}', [InstructorController::class, 'destroyMaterial'])->name('materials.destroy');
    
    // Peserta Kursus Routes
    Route::get('/courses/{courseSlug}/students', [InstructorController::class, 'students'])->name('students');
    
    // Sertifikat Routes
    Route::post('/certificates/{enrollmentId}/generate', [InstructorController::class, 'generateCertificate'])->name('certificates.generate');
    
    // Profile Routes
    Route::get('/profile', [InstructorController::class, 'profile'])->name('profile');
    Route::put('/profile', [InstructorController::class, 'updateProfile'])->name('profile.update');
});

// Student Routes
Route::prefix('student')->name('student.')->middleware(['auth', 'checkrole:student,user'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
    Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('my-courses');
    Route::get('/schedule', [StudentController::class, 'schedule'])->name('schedule');
    Route::get('/payment', [StudentController::class, 'payment'])->name('payment');
    Route::post('/payment/{payment}/upload-proof', [StudentController::class, 'uploadPaymentProof'])->name('payment.upload-proof');
    Route::get('/certificate', [StudentController::class, 'certificate'])->name('certificate');
    
    // Chat Routes
    Route::get('/chat', [StudentController::class, 'chat'])->name('chat');
    Route::post('/chat/create', [StudentController::class, 'createOrGetConversation'])->name('chat.create');
    Route::get('/chat/{conversationId}', [StudentController::class, 'showChat'])->name('chat.show');
    Route::post('/chat/{conversationId}/message', [StudentController::class, 'sendMessage'])->name('chat.send-message');
    
    // Materials Routes
    Route::get('/materials', [StudentController::class, 'materials'])->name('materials');
    Route::get('/materials/{courseSlug}', [StudentController::class, 'materials'])->name('materials.course');
    
    // Reschedule Routes
    Route::get('/reschedule', [StudentController::class, 'reschedule'])->name('reschedule');
    Route::post('/reschedule', [StudentController::class, 'storeReschedule'])->name('reschedule.store');
    
    // Session Routes
    Route::get('/sessions/{session}', [StudentController::class, 'showSession'])->name('sessions.show');
    
    // Profile Routes
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::post('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    
    // Dashboard Component Routes
    Route::get('/active-days-counter', [ActiveDaysCounterController::class, 'index'])->name('active-days-counter');
    Route::get('/certificate-ready', [CertificateReadyController::class, 'index'])->name('certificate-ready');
    Route::get('/chat-shortcut', [ChatShortcutController::class, 'index'])->name('chat-shortcut');
    Route::get('/continue-learning', [ContinueLearningController::class, 'index'])->name('continue-learning');
    Route::get('/next-session', [NextSessionController::class, 'index'])->name('next-session');
    Route::get('/payment-status', [PaymentStatusController::class, 'index'])->name('payment-status');
});