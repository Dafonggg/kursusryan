<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client as TwilioClient;

class WhatsAppChannel
{
    /**
     * Send the given notification via WhatsApp using Twilio.
     */
    public function send($notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        // Cek apakah user sudah opt-in untuk WhatsApp
        $profile = $notifiable->profile;
        if (!$profile || !$profile->whatsapp_opt_in || !$profile->phone) {
            return;
        }

        // Validasi format E.164
        $phone = $this->formatPhoneNumber($profile->phone);
        if (!$phone) {
            \Log::warning("Invalid phone number format for user {$notifiable->id}: {$profile->phone}");
            return;
        }

        $twilioSid = config('services.twilio.sid');
        $twilioToken = config('services.twilio.token');
        $twilioFrom = config('services.twilio.whatsapp_from');

        if (!$twilioSid || !$twilioToken || !$twilioFrom) {
            \Log::error('Twilio credentials not configured');
            return;
        }

        try {
            $twilio = new TwilioClient($twilioSid, $twilioToken);
            
            $twilio->messages->create(
                "whatsapp:{$phone}",
                [
                    'from' => "whatsapp:{$twilioFrom}",
                    'body' => $message['body'] ?? '',
                ]
            );

            \Log::info("WhatsApp notification sent to {$phone} for user {$notifiable->id}");
        } catch (\Exception $e) {
            \Log::error("Failed to send WhatsApp notification: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Format phone number to E.164 format
     * Example: 081234567890 -> +6281234567890
     */
    protected function formatPhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // If starts with 0, replace with country code 62 (Indonesia)
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // If doesn't start with country code, assume Indonesia (62)
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        // Add + prefix for E.164 format
        return '+' . $phone;
    }
}

