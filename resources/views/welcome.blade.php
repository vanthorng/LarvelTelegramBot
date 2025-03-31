<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>

    <!-- Tailwind CSS CDN (if not compiled via Laravel Mix/Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-violet-600 text-white min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <h1 class="text-5xl font-extrabold mb-4">👋 Welcome to Your Laravel App</h1>
        <p class="text-xl mb-8 opacity-90">This is your starting point. Build cool stuff. Automate Telegram. Save the world 😎</p>

        <div class="flex justify-center gap-4 flex-wrap">
            <a href="{{ route('telegram.index') }}" class="bg-white text-purple-700 px-6 py-3 rounded-lg font-semibold shadow hover:scale-105 transition">
                📂 File Manager
            </a>

            <a href="{{ route('telegram.upload') }}" class="bg-purple-700 px-6 py-3 rounded-lg font-semibold hover:bg-purple-800 transition">
                📤 Upload New File
            </a>

            <a href="https://core.telegram.org/bots/api" target="_blank" class="bg-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                📚 Telegram API Docs
            </a>
        </div>

        <div class="mt-10 text-sm opacity-60">
            Laravel {{ Illuminate\Foundation\Application::VERSION }} | PHP {{ PHP_VERSION }}
        </div>
    </div>
</body>
</html>
