<?php

namespace App\Console\Commands;

use App\Models\CourseSession;
use App\Notifications\ReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendSessionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications 1 hour before course sessions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mencari sesi yang akan dimulai dalam 1 jam...');

        // Cari session yang akan dimulai dalam 1 jam (dengan toleransi ±5 menit)
        $now = now();
        $oneHourFromNow = $now->copy()->addHour();
        
        $sessions = CourseSession::with(['course.enrollments.user.profile'])
            ->whereBetween('scheduled_at', [
                $oneHourFromNow->copy()->subMinutes(5),
                $oneHourFromNow->copy()->addMinutes(5),
            ])
            ->where('scheduled_at', '>', $now) // Pastikan belum lewat
            ->get();

        if ($sessions->isEmpty()) {
            $this->info('Tidak ada sesi yang perlu dikirim notifikasi.');
            return 0;
        }

        $this->info("Ditemukan {$sessions->count()} sesi yang perlu dikirim notifikasi.");

        $totalSent = 0;

        foreach ($sessions as $session) {
            // Cek apakah sudah pernah dikirim notifikasi untuk session ini
            $alreadySent = DB::table('session_reminder_logs')
                ->where('course_session_id', $session->id)
                ->exists();

            if ($alreadySent) {
                $this->line("Notifikasi untuk sesi '{$session->title}' (ID: {$session->id}) sudah dikirim sebelumnya. Dilewati.");
                continue;
            }

            // Dapatkan semua user yang ter-enroll
            $enrolledUsers = $session->getEnrolledUsers();

            if ($enrolledUsers->isEmpty()) {
                $this->line("Tidak ada peserta ter-enroll untuk sesi '{$session->title}' (ID: {$session->id}).");
                continue;
            }

            $this->line("Mengirim notifikasi untuk sesi '{$session->title}' ke {$enrolledUsers->count()} peserta...");

            $sentCount = 0;
            foreach ($enrolledUsers as $user) {
                try {
                    $user->notify(new ReminderNotification($session));
                    $sentCount++;
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim notifikasi ke user {$user->id}: " . $e->getMessage());
                    $this->error("Gagal mengirim notifikasi ke {$user->name} (ID: {$user->id})");
                }
            }

            // Tandai bahwa notifikasi sudah dikirim untuk session ini
            DB::table('session_reminder_logs')->insert([
                'course_session_id' => $session->id,
                'sent_at' => now(),
                'recipients_count' => $sentCount,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $totalSent += $sentCount;
            $this->info("✓ Notifikasi berhasil dikirim untuk sesi '{$session->title}' ke {$sentCount} peserta.");
        }

        $this->info("Selesai! Total {$totalSent} notifikasi dikirim.");
        return 0;
    }
}
