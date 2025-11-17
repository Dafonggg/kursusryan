# Setup Notifikasi 1 Jam Sebelum Sesi

Dokumentasi ini menjelaskan cara setup sistem notifikasi yang mengirim pengingat 1 jam sebelum sesi kursus dimulai melalui WhatsApp dan Gmail.

## Fitur

- ✅ Notifikasi Email (Gmail) otomatis ke semua peserta
- ✅ Notifikasi WhatsApp (via Twilio) untuk peserta yang sudah opt-in
- ✅ Scheduler berjalan setiap menit untuk mencari session yang akan dimulai dalam 1 jam
- ✅ Queue system untuk memproses notifikasi secara asynchronous
- ✅ Tracking untuk mencegah duplikasi notifikasi

## Instalasi

### 1. Install Dependencies

```bash
composer require twilio/sdk
```

### 2. Jalankan Migration

```bash
php artisan migrate
```

Migration yang akan dijalankan:
- `add_whatsapp_opt_in_to_user_profiles_table` - Menambahkan field `whatsapp_opt_in` di tabel `user_profiles`
- `create_session_reminder_logs_table` - Tabel untuk tracking notifikasi yang sudah dikirim

### 3. Konfigurasi Environment Variables

Tambahkan ke file `.env`:

```env
# Twilio Configuration (untuk WhatsApp)
TWILIO_SID=your_twilio_account_sid
TWILIO_AUTH_TOKEN=your_twilio_auth_token
TWILIO_WHATSAPP_FROM=+14155238886  # Twilio Sandbox atau nomor WhatsApp Business Anda

# Mail Configuration (untuk Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Queue Configuration
QUEUE_CONNECTION=database  # atau redis/sqs sesuai kebutuhan
```

**Catatan untuk Gmail:**
- Gunakan App Password, bukan password biasa
- Aktifkan 2-Step Verification di Google Account
- Generate App Password di: https://myaccount.google.com/apppasswords

**Catatan untuk Twilio:**
- Daftar di https://www.twilio.com
- Untuk testing, gunakan Twilio Sandbox (format: `whatsapp:+14155238886`)
- Untuk production, daftar WhatsApp Business API di Twilio

### 4. Setup Queue Worker

Jalankan queue worker untuk memproses notifikasi:

```bash
php artisan queue:work
```

Atau untuk development (dengan auto-reload):

```bash
php artisan queue:listen
```

**Untuk Production:**
Setup supervisor atau systemd service untuk menjalankan queue worker secara otomatis.

### 5. Setup Scheduler

Pastikan cron job sudah dikonfigurasi untuk menjalankan Laravel scheduler:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Atau jika menggunakan Laravel Sail:

```bash
* * * * * cd /path-to-your-project && ./vendor/bin/sail artisan schedule:run >> /dev/null 2>&1
```

## Penggunaan

### Mengaktifkan WhatsApp Opt-in untuk User

Untuk mengaktifkan notifikasi WhatsApp, user harus:
1. Memiliki nomor telepon di `user_profiles.phone` (format E.164, contoh: `+6281234567890`)
2. Mengaktifkan `whatsapp_opt_in = true` di `user_profiles`

Contoh kode:

```php
$user = User::find(1);
$user->profile->update([
    'phone' => '+6281234567890',
    'whatsapp_opt_in' => true,
]);
```

### Format Nomor Telepon

Sistem akan otomatis mengkonversi nomor telepon ke format E.164:
- `081234567890` → `+6281234567890`
- `6281234567890` → `+6281234567890`
- `+6281234567890` → `+6281234567890` (sudah benar)

### Testing

Untuk test manual, jalankan command:

```bash
php artisan sessions:send-reminders
```

Command ini akan:
1. Mencari semua session yang akan dimulai dalam 1 jam (±5 menit toleransi)
2. Mengirim notifikasi ke semua peserta yang ter-enroll
3. Mencatat log di tabel `session_reminder_logs`

## Arsitektur

### Komponen Utama

1. **ReminderNotification** (`app/Notifications/ReminderNotification.php`)
   - Notification class yang mengirim ke 2 channel: mail dan whatsapp
   - Implement `ShouldQueue` untuk diproses via queue

2. **WhatsAppChannel** (`app/Notifications/Channels/WhatsAppChannel.php`)
   - Custom notification channel untuk WhatsApp via Twilio
   - Validasi format E.164 dan cek opt-in

3. **SendSessionReminders** (`app/Console/Commands/SendSessionReminders.php`)
   - Command yang mencari session dan mengirim notifikasi
   - Mencegah duplikasi dengan tracking di `session_reminder_logs`

4. **CourseSession Model**
   - Method `getEnrolledUsers()` untuk mendapatkan peserta yang ter-enroll

### Flow

1. Scheduler (`routes/console.php`) menjalankan command setiap menit
2. Command mencari session yang `scheduled_at` ≈ `now() + 1 hour`
3. Untuk setiap session, dapatkan semua enrolled users (status active)
4. Dispatch `ReminderNotification` ke setiap user (via queue)
5. Queue worker memproses notifikasi:
   - Selalu kirim email
   - Kirim WhatsApp jika user sudah opt-in
6. Log disimpan di `session_reminder_logs` untuk mencegah duplikasi

## Troubleshooting

### Notifikasi tidak terkirim

1. **Cek Queue Worker:**
   ```bash
   php artisan queue:work --verbose
   ```

2. **Cek Log:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Cek Failed Jobs:**
   ```bash
   php artisan queue:failed
   ```

### WhatsApp tidak terkirim

1. Pastikan Twilio credentials sudah benar di `.env`
2. Pastikan nomor telepon sudah format E.164
3. Pastikan `whatsapp_opt_in = true` di database
4. Cek log untuk error dari Twilio API

### Email tidak terkirim

1. Pastikan mail configuration sudah benar di `.env`
2. Test dengan:
   ```bash
   php artisan tinker
   Mail::raw('Test', function($msg) { $msg->to('your@email.com')->subject('Test'); });
   ```

## Catatan Penting

- Notifikasi hanya dikirim sekali per session (tracking via `session_reminder_logs`)
- Toleransi waktu: ±5 menit dari 1 jam sebelum session
- Hanya user dengan enrollment status `active` yang menerima notifikasi
- Queue harus berjalan untuk notifikasi diproses

