<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login') | Sistem Evaluasi Tenaga Kerja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 antialiased text-slate-800 bg-slate-100">
    <div class="w-full max-w-md">
        @yield('content')
    </div>
</body>
</html>
