<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-glass {
                background: rgba(255, 255, 255, 0.78);
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
            }
            .app-bg {
                background:
                    radial-gradient(circle at top left, rgba(59, 130, 246, 0.10), transparent 30%),
                    radial-gradient(circle at top right, rgba(99, 102, 241, 0.10), transparent 24%),
                    linear-gradient(135deg, #f8fbff 0%, #ffffff 48%, #f6f7ff 100%);
            }
        </style>

        <script>
            window.tailwind = window.tailwind || {};
            window.tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'ui-sans-serif', 'system-ui']
                        }
                    }
                }
            };
        </script>
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Tailwind CDN fallback for quick visual preview when Vite is not running -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased text-slate-800">
        <div class="min-h-screen app-bg">
            <div class="fixed inset-0 pointer-events-none overflow-hidden">
                <div class="absolute -top-28 -left-16 h-72 w-72 rounded-full bg-blue-500/10 blur-3xl"></div>
                <div class="absolute top-40 -right-20 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl"></div>
                <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-sky-400/10 blur-3xl"></div>
            </div>

            <div class="bg-glass shadow-sm sticky top-0 z-50 border-b border-slate-200/70">
                @include('layouts.navigation')
            </div>

            @isset($header)
                <header class="relative border-b border-slate-200/70 bg-white/45 backdrop-blur-sm">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="relative py-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
