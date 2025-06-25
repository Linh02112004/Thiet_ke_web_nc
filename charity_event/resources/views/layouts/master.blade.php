<!-- resources/views/layouts/master.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '🌱 HY VỌNG')</title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">

    @php $role = Auth::user()->role ?? null; @endphp
    @if ($role === 'admin')
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @elseif ($role === 'organization')
        <link rel="stylesheet" href="{{ asset('css/organization.css') }}">
    @elseif ($role === 'donor')
        <link rel="stylesheet" href="{{ asset('css/donor.css') }}">
    @endif

    @stack('styles')
</head>
<body>

    @include('components.header')

    <main>
        @yield('content')  
    </main>

    @include('components.footer')

    @stack('scripts')
</body>
</html>
