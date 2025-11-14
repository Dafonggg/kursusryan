# Setup Google OAuth untuk Login/Register

## Masalah yang Sering Terjadi

Jika Anda melihat error **"Failed to authenticate with Google. Please try again."**, kemungkinan besar Google OAuth belum dikonfigurasi dengan benar.

## Langkah-langkah Setup

### 1. Buat Google OAuth Credentials di Google Cloud Console

1. Buka https://console.cloud.google.com/
2. Buat project baru atau pilih project yang sudah ada
3. Aktifkan **Google+ API**:
   - Pilih "APIs & Services" > "Library"
   - Cari "Google+ API" dan klik "Enable"

4. Buat **OAuth 2.0 Client ID**:
   - Pilih "APIs & Services" > "Credentials"
   - Klik "Create Credentials" > "OAuth client ID"
   - Jika diminta, pilih "Web application"
   - Isi form:
     - **Name**: Kursus Ryan Komputer (atau nama yang Anda inginkan)
     - **Authorized redirect URIs**: 
       - Development: `http://localhost:8000/auth/google/callback`
       - Production: `https://yourdomain.com/auth/google/callback`
   - Klik "Create"
   - **Copy** `Client ID` dan `Client Secret`

### 2. Tambahkan Credentials ke File .env

Tambahkan baris berikut di file `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id-di-sini
GOOGLE_CLIENT_SECRET=your-client-secret-di-sini
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Untuk Production**, ganti dengan:
```env
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

### 3. Clear Config Cache

Jalankan perintah berikut:

```bash
php artisan config:clear
php artisan config:cache
```

### 4. Test Google OAuth

1. Buka halaman login: `http://localhost:8000/login`
2. Klik tombol **"Sign in with Google"**
3. Pilih akun Google Anda
4. Authorize aplikasi
5. Anda akan di-redirect kembali ke aplikasi dan otomatis login

## Troubleshooting

### Error: "Google OAuth belum dikonfigurasi"
- Pastikan `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` sudah diisi di file `.env`
- Jalankan `php artisan config:clear`

### Error: "redirect_uri_mismatch"
- Pastikan redirect URI di Google Cloud Console sama persis dengan yang di `.env`
- Harus exact match, termasuk `http://` vs `https://`

### Error: "Invalid client"
- Pastikan Client ID dan Client Secret yang di-copy sudah benar
- Jangan ada spasi atau karakter tambahan

### Error: "Access blocked"
- Pastikan Google+ API sudah diaktifkan
- Pastikan OAuth consent screen sudah dikonfigurasi di Google Cloud Console

## Catatan Penting

1. **Untuk Development**: Pastikan menggunakan `http://localhost:8000/auth/google/callback`
2. **Untuk Production**: Ganti dengan domain Anda yang sebenarnya
3. **OAuth Consent Screen**: Di Google Cloud Console, pastikan Anda sudah mengisi OAuth consent screen dengan informasi aplikasi Anda

## Cek Log Error

Jika masih ada masalah, cek log Laravel:
```bash
tail -f storage/logs/laravel.log
```

Error yang lebih detail akan tercatat di file log ini.

