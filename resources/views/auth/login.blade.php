<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Synthesis Huis SPK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        slate: {
                            900: '#0F172A',
                            800: '#1E293B',
                        },
                        gold: {
                            400: '#C5A880',
                            500: '#D4AF37',
                            600: '#B5952F',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .bg-image-side {
            background-image: url('{{ asset("images/login-bg.jpg") }}');
            background-size: cover;
            background-position: center;
        }

        .bg-image-mobile {
            background-image: url('{{ asset("images/login-bg.jpg") }}');
            background-size: cover;
            background-position: center;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-delay-100 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .animate-delay-200 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .animate-delay-300 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .animate-delay-400 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        .image-overlay {
            background: linear-gradient(135deg,
                    rgba(15, 23, 42, 0.3) 0%,
                    rgba(15, 23, 42, 0.1) 50%,
                    rgba(212, 175, 55, 0.05) 100%);
        }
    </style>
</head>

<body class="bg-slate-900 font-sans text-white h-screen overflow-hidden">

    <div class="flex h-full">
        <div class="hidden lg:flex lg:w-3/5 xl:w-2/3 bg-image-side relative">
            <div class="absolute inset-0 image-overlay"></div>
            <div class="absolute bottom-10 left-10 z-10 animate-fade-in-up">
                <h2 class="text-4xl xl:text-5xl font-bold text-white drop-shadow-lg">Synthesis<span
                        class="text-gold-500">Huis</span></h2>
            </div>
        </div>

        <div class="w-full lg:w-2/5 xl:w-1/3 flex items-center justify-center relative bg-image-mobile lg:bg-none">
            <div class="absolute inset-0 bg-slate-900/85 lg:bg-slate-900 lg:bg-opacity-100"></div>
            <div
                class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-gold-500 rounded-full mix-blend-multiply filter blur-[120px] opacity-10 pointer-events-none">
            </div>

            <div class="w-full max-w-md px-8 relative z-10">
                <div class="mb-10 text-center animate-fade-in-up">
                    <h1 class="text-4xl font-bold text-white mb-2 tracking-tight">Synthesis<span
                            class="text-gold-500">Huis</span></h1>
                    <p class="text-gray-400 text-sm">Sistem Pendukung Keputusan Digital Marketing</p>
                    <div class="mt-6 border-t border-slate-700/50 pt-6">
                        <h2 class="text-2xl font-semibold text-white mb-1">Selamat Datang</h2>
                        <p class="text-gray-400 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div
                        class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/50 flex items-start space-x-3 animate-fade-in-up">
                        <svg class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-red-300">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="animate-fade-in-up animate-delay-100">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="E-mail@gmail.com" required autofocus
                            class="w-full px-4 py-3 bg-slate-800/50 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent transition-all duration-300">
                    </div>

                    <div class="animate-fade-in-up animate-delay-200">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 bg-slate-800/50 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent transition-all duration-300"
                            placeholder="••••••••">
                    </div>

                    <div class="flex items-center animate-fade-in-up animate-delay-300">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-gray-700 bg-slate-800 text-gold-500 focus:ring-gold-500 focus:ring-offset-slate-900">
                        <label for="remember" class="ml-2 block text-sm text-gray-400">
                            Ingat Saya
                        </label>
                    </div>

                    <div class="animate-fade-in-up animate-delay-400">
                        <button type="submit"
                            class="w-full py-3 px-4 bg-gold-500 hover:bg-gold-600 text-slate-900 font-semibold rounded-lg shadow-lg shadow-gold-500/30 transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-gold-500">
                            Masuk ke Dashboard
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm text-gray-500 animate-fade-in-up animate-delay-400">
                    &copy; {{ date('Y') }} Synthesis Huis SPK. All rights reserved.
                </div>
            </div>
        </div>
    </div>

</body>

</html>