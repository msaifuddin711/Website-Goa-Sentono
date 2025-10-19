{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Goa Sentono</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/logofix.png') }}" />
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .login-background {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-green) 50%, var(--accent-green) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .login-background::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="200" cy="200" r="100" fill="rgba(255,255,255,0.03)"/><circle cx="800" cy="300" r="150" fill="rgba(255,255,255,0.02)"/><circle cx="400" cy="600" r="80" fill="rgba(255,255,255,0.04)"/><circle cx="700" cy="700" r="120" fill="rgba(255,255,255,0.02)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }
        
        .cave-icon {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .input-glass {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
        }
        
        .input-glass::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .input-glass:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--light-beige) 0%, var(--cream) 100%);
            color: var(--primary-dark);
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, var(--cream) 0%, var(--light-beige) 100%);
        }
        
        .floating-cave {
            position: absolute;
            opacity: 0.1;
            animation: float-random 15s ease-in-out infinite;
        }
        
        @keyframes float-random {
            0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg); }
            25% { transform: translateY(-30px) translateX(20px) rotate(90deg); }
            50% { transform: translateY(-60px) translateX(-10px) rotate(180deg); }
            75% { transform: translateY(-20px) translateX(15px) rotate(270deg); }
        }
        
        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 0 40px rgba(255, 255, 255, 0.5);
                transform: scale(1.05);
            }
        }
        
        .slide-up {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fecaca;
        }
        
        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #bbf7d0;
        }

        .fa-icon {
            transition: all 0.3s ease;
        }

        .fa-icon:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="min-h-screen login-background flex items-center justify-center p-4">
    <div class="floating-cave" style="top: 10%; left: 10%; width: 60px; height: 60px;">
        <i class="fas fa-mountain text-white text-4xl fa-icon"></i>
    </div>
    
    <div class="floating-cave" style="top: 70%; right: 15%; width: 40px; height: 40px; animation-delay: -5s;">
        <i class="fas fa-star text-white text-3xl fa-icon"></i>
    </div>
    
    <div class="floating-cave" style="bottom: 20%; left: 80%; width: 50px; height: 50px; animation-delay: -10s;">
        <i class="fas fa-gem text-white text-3xl fa-icon"></i>
    </div>

    <div class="w-full max-w-md slide-up">
        <div class="text-center mb-8">
            <div class="cave-icon w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6 pulse-glow">
                <img src="{{ asset('images/logofix.png') }}" alt="Logo Goa Sentono" class="w-12 h-12 object-contain">
            </div>
            
            <h2 class="text-xl font-semibold text-white mb-2">Situs Goa Sentono</h2>
            <p class="text-white text-opacity-80">Masuk untuk mengelola konten website</p>
        </div>

        <div class="glass-card rounded-3xl p-8">
            @if (session('status'))
                <div class="success-message rounded-lg p-4 mb-6 flex items-center">
                    <i class="fas fa-check-circle text-lg mr-3"></i>
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="block text-white text-sm font-medium">
                        <i class="fas fa-envelope mr-2"></i>{{ __('Email') }}
                    </label>
                    <div class="relative">
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus 
                            autocomplete="username"
                            class="input-glass w-full pl-4 pr-4 py-3 rounded-xl text-white placeholder-white placeholder-opacity-70 focus:outline-none transition duration-300"
                            placeholder="email"
                        >
                    </div>
                    @error('email')
                        <div class="error-message rounded-lg p-3 flex items-center">
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-white text-sm font-medium">
                        <i class="fas fa-lock mr-2"></i>{{ __('Password') }}
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            class="input-glass w-full pl-4 pr-12 py-3 rounded-xl text-white placeholder-white placeholder-opacity-70 focus:outline-none transition duration-300"
                            placeholder="password"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-white text-opacity-70 hover:text-white transition duration-200"
                        >
                            <i id="eye-open" class="fas fa-eye"></i>
                            <i id="eye-closed" class="fas fa-eye-slash hidden"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-message rounded-lg p-3 flex items-center">
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <button 
                    type="submit"
                    class="btn-primary w-full py-3 px-6 rounded-xl font-semibold text-lg transition duration-300 transform hover:scale-105"
                >
                    <div class="flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        {{ __('Log in') }}
                    </div>
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.innerHTML = `
                <div class="flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Memproses...
                </div>
            `;
            submitBtn.disabled = true;
        });

        document.querySelectorAll('.fa-icon').forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.2) rotate(10deg)';
            });
            
            icon.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });
        });
    </script>
</body>
</html>