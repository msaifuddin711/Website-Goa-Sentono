<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<body class="antialiased">
    @if (Route::has('login'))
    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        @auth
            <a href="{{ url('/dashboard') }}" class="text-sm underline">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-sm underline">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 text-sm underline">Register</a>
            @endif
        @endauth
    </div>
    @endif

    <div class="flex justify-center items-center min-h-screen">
        <h1 class="text-5xl font-bold">Laravel</h1>
    </div>
</body>
</html>
