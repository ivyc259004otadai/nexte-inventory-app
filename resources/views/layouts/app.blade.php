<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Nexte'))</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="app-shell">
    <div class="app-layout">
        <x-sidebar :current-store="$currentStoreName ?? '渋谷店'" />

        <main class="app-content">
            @yield('content')
        </main>
    </div>
</body>

</html>