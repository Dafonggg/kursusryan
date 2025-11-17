<?php

namespace App\Notifications;

use App\Models\CourseSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\WhatsAppChannel;

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public CourseSession $session
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['mail'];
        
        // Tambahkan WhatsApp channel jika user sudah opt-in
        $profile = $notifiable->profile;
        if ($profile && $profile->whatsapp_opt_in && $profile->phone) {
            $channels[] = WhatsAppChannel::class;
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $session = $this->session;
        $course = $session->course;
        $scheduledAt = $session->scheduled_at->format('d F Y, H:i');
        
        $message = (new MailMessage)
            ->subject("Pengingat: Sesi {$course->title} akan dimulai dalam 1 jam")
            ->greeting("Halo {$notifiable->name}!")
            ->line("Ini adalah pengingat bahwa sesi kursus Anda akan dimulai dalam 1 jam.")
            ->line("**Detail Sesi:**")
            ->line("- **Judul:** {$session->title}")
            ->line("- **Kursus:** {$course->title}")
            ->line("- **Waktu:** {$scheduledAt}")
            ->line("- **Durasi:** {$session->duration_minutes} menit");

        if ($session->is_online && $session->meeting_url) {
            $message->action('Bergabung ke Sesi', $session->meeting_url);
        } elseif ($session->is_offline && $session->location) {
            $message->line("- **Lokasi:** {$session->location}");
        }

        $message->line("Terima kasih dan sampai jumpa di sesi!");

        return $message;
    }

    /**
     * Get the WhatsApp representation of the notification.
     *
     * @return array<string, string>
     */
    public function toWhatsApp(object $notifiable): array
    {
        $session = $this->session;
        $course = $session->course;
        $scheduledAt = $session->scheduled_at->format('d F Y, H:i');
        
        $body = "🔔 *Pengingat Sesi Kursus*\n\n";
        $body .= "Halo {$notifiable->name}!\n\n";
        $body .= "Sesi kursus Anda akan dimulai dalam *1 jam*.\n\n";
        $body .= "*Detail Sesi:*\n";
        $body .= "• Judul: {$session->title}\n";
        $body .= "• Kursus: {$course->title}\n";
        $body .= "• Waktu: {$scheduledAt}\n";
        $body .= "• Durasi: {$session->duration_minutes} menit\n";
        
        if ($session->is_online && $session->meeting_url) {
            $body .= "\n🔗 Link: {$session->meeting_url}\n";
        } elseif ($session->is_offline && $session->location) {
            $body .= "• Lokasi: {$session->location}\n";
        }
        
        $body .= "\nTerima kasih dan sampai jumpa di sesi!";

        return [
            'body' => $body,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'session_id' => $this->session->id,
            'session_title' => $this->session->title,
            'course_title' => $this->session->course->title,
            'scheduled_at' => $this->session->scheduled_at->toIso8601String(),
        ];
    }
}
