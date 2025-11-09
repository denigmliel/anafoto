@extends('layouts.auth')

@section('title', 'Login - ANA FOTOCOPY')

@push('head')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #c9302c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 480px;
            width: 100%;
            display: flex;
            flex-direction: column;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-left {
            background: linear-gradient(135deg, #c9302c 0%, #e74c3c 100%);
            padding: 48px 40px 36px;
            flex: none;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            gap: 12px;
        }

        .logo {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 4px solid white;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .login-left h1 {
            font-size: 32px;
            margin-bottom: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .login-right {
            padding: 36px 40px 44px;
            flex: none;
            background: white;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h2 {
            color: #1e3c72;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1e3c72;
            background: white;
            box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
        }

        .form-group input.is-invalid {
            border-color: #c9302c;
        }

        .invalid-feedback {
            color: #c9302c;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .forgot-password {
            color: #c9302c;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #c9302c 0%, #e74c3c 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(201, 48, 44, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(201, 48, 44, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: #fee;
            color: #c9302c;
            border: 1px solid #fcc;
        }

        .alert-success {
            background: #efe;
            color: #28a745;
            border: 1px solid #cfc;
        }

        @media (max-width: 640px) {
            .login-container {
                border-radius: 16px;
            }

            .login-left {
                padding: 36px 24px 28px;
            }

            .login-right {
                padding: 28px 24px 36px;
            }

        }
    </style>
@endpush

@section('content')
    <div class="login-container">
        <div class="login-left">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="Logo ANA Fotocopy">
            </div>
            <h1>ANA FOTOCOPY</h1>
        </div>

        <div class="login-right">
            <div class="login-header">
                <h2>Selamat Datang!</h2>
                <p>Silakan login untuk melanjutkan</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="@error('email') is-invalid @enderror"
                        required
                        autofocus
                        placeholder="user@anafotocopy.com"
                    >
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="@error('password') is-invalid @enderror"
                        required
                        placeholder="••••••••"
                    >
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-forgot">
                    <div class="remember-me">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label for="remember">Ingat Saya</label>
                    </div>
                    <a href="#" class="forgot-password">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-login">LOGIN</button>
            </form>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; font-size: 12px; color: #999; text-align: center;">
                <p><strong>Akun Default:</strong></p>
                <p>Admin: admin@anafotocopy.com | password</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
@endpush
