<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <title>@yield('title', 'Register | Kursus Ryan Komputer')</title>
</head>
<body>
    <div class="container">
        <div class="login__content">
            <img src="{{ asset('images/bg-login.png') }}" alt="register image" class="login__img">

            <form action="{{ route('register.post') }}" method="POST" class="login__form">
                @csrf
                <div>
                    <h1 class="login__title">
                        <span>Create</span> Account
                    </h1>
                    <p class="login__description">
                        Welcome! Please register to create your account.
                    </p>
                </div>
                
                <div>
                    <div class="login__inputs">
                        <div>
                            <label for="input-name" class="login__label">Nama Lengkap</label>
                            <input type="text" 
                                   name="name" 
                                   placeholder="Enter your full name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   class="login__input" 
                                   id="input-name">
                            @error('name')
                                <div style="color: #EA4335; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="input-email" class="login__label">Email</label>
                            <input type="email" 
                                   name="email" 
                                   placeholder="Enter your email address" 
                                   value="{{ old('email') }}" 
                                   required 
                                   class="login__input" 
                                   id="input-email">
                            @error('email')
                                <div style="color: #EA4335; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="input-email-confirm" class="login__label">Konfirmasi Email</label>
                            <input type="email" 
                                   name="email_confirmation" 
                                   placeholder="Confirm your email address" 
                                   required 
                                   class="login__input" 
                                   id="input-email-confirm">
                            @error('email_confirmation')
                                <div style="color: #EA4335; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="input-phone" class="login__label">Nomor Handphone</label>
                            <input type="tel" 
                                   name="phone" 
                                   placeholder="Enter your phone number" 
                                   value="{{ old('phone') }}" 
                                   required 
                                   class="login__input" 
                                   id="input-phone">
                            @error('phone')
                                <div style="color: #EA4335; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="input-pass" class="login__label">Kata Sandi</label>

                            <div class="login__box">
                                <input type="password" 
                                       name="password" 
                                       placeholder="Enter your password" 
                                       required 
                                       class="login__input" 
                                       id="input-pass">
                                <i class="ri-eye-off-line login__eye" id="input-icon"></i>
                            </div>
                            @error('password')
                                <div style="color: #EA4335; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="input-pass-confirm" class="login__label">Konfirmasi Kata Sandi</label>

                            <div class="login__box">
                                <input type="password" 
                                       name="password_confirmation" 
                                       placeholder="Confirm your password" 
                                       required 
                                       class="login__input" 
                                       id="input-pass-confirm">
                                <i class="ri-eye-off-line login__eye" id="input-icon-confirm"></i>
                            </div>
                            @error('password_confirmation')
                                <div style="color: #EA4335; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('email_confirmation') && !$errors->has('phone') && !$errors->has('password') && !$errors->has('password_confirmation'))
                        <div style="color: #EA4335; font-size: 0.75rem; margin-bottom: 0.5rem;">
                            <ul style="list-style: disc; margin-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="login__social">
                        <a href="{{ route('auth.google') }}" class="login__social-button">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 0.5rem;">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Sign up with Google
                        </a>
                    </div>

                    <div style="display: flex; align-items: center; margin: 1.5rem 0;">
                        <div style="flex: 1; height: 1px; background-color: #e0e0e0;"></div>
                        <span style="padding: 0 1rem; color: #8e8e8e; font-size: 0.875rem;">or</span>
                        <div style="flex: 1; height: 1px; background-color: #e0e0e0;"></div>
                    </div>

                    <div class="login__buttons">
                        <button type="submit" class="login__button">Register</button>
                        <a href="{{ route('login') }}" class="login__button login__button-ghost" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">Sign In</a>
                    </div>

                    <div style="text-align: center; margin-top: 1rem;">
                        <span style="font-size: var(--smaller-font-size);">Sudah punya akun? <a href="{{ route('login') }}" class="login__forgot">Login</a></span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--=============== MAIN JS ===============-->
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
