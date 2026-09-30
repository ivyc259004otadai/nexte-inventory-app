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
        <x-sidebar :display-scope="$displayScope ?? '全店舗'" :current-page="$sidebarCurrentPage ?? 'dashboard'" />

        <main class="app-content">
            @yield('content')
        </main>
    </div>

    @if (session('success'))
    <div class="toast toast--success" role="status" aria-live="polite">
        <div class="toast__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 7.5v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                <circle cx="12" cy="16.2" r="1" fill="currentColor" />
                <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6" />
            </svg>
        </div>
        <div class="toast__body">
            <p class="toast__title">完了</p>
            <p class="toast__message">{{ session('success') }}</p>
        </div>
    </div>
    @endif
</body>

</html>
