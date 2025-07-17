<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjZGMxNDQ3IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZD0iTTEyIDMgTDIgOSA2IDkgNiAxNSAxOCAxNSAxOCA5IDE4IDkgMTIgMTgiLz48L3N2Zz4=">

    <!-- Alpine.js and Tailwind -->
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false, notifOpen: false }" class="bg-gradient-to-r from-pink-400 via-purple-600 to-pink-600 min-h-screen text-gray-800">

    <!-- Sidebar -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-50 flex" x-cloak>
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="sidebarOpen = false"></div>

        <!-- Sidebar Panel -->
        <aside class="relative w-64 bg-white shadow-lg z-50 p-6 space-y-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-purple-700">Menu</h2>
                <button @click="sidebarOpen = false" class="text-gray-600 hover:text-red-600">✕</button>
            </div>
            <nav class="space-y-2">
            <a href="{{ route('bookings.create') }}"
            class="block px-4 py-2 rounded hover:bg-purple-100 transition {{ request()->routeIs('bookings.create') ? 'bg-purple-100 font-semibold text-purple-700' : 'text-gray-700' }}">
                ➕ Create Booking
            </a>
            
            <a href="{{ route('bookings.index') }}"
            class="block px-4 py-2 rounded hover:bg-purple-100 transition {{ request()->routeIs('bookings.index') ? 'bg-purple-100 font-semibold text-purple-700' : 'text-gray-700' }}">
                📋 View Bookings
            </a>

            @can('viewAny', App\Models\Booking::class)
                <a href="{{ route('admin.bookings') }}"
                class="block px-4 py-2 rounded hover:bg-purple-100 transition {{ request()->routeIs('admin.bookings') ? 'bg-purple-100 font-semibold text-purple-700' : 'text-gray-700' }}">
                    🛠 All Bookings (Admin)
                </a>
            @endcan

            <a href="{{ route('profile.edit') }}"
            class="block px-4 py-2 rounded hover:bg-purple-100 transition {{ request()->routeIs('profile.edit') ? 'bg-purple-100 font-semibold text-purple-700' : 'text-gray-700' }}">
                👤 Profile
            </a>
        </nav>

        </aside>
    </div>

    <!-- Top Navbar -->
    <nav class="bg-white shadow-md h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40">
        <!-- Left: Hamburger and Title -->
        <div class="flex items-center space-x-4">
            <button @click="sidebarOpen = true" class="text-purple-700 focus:outline-none">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="{{ route('dashboard') }}" class="text-lg font-bold text-purple-700">Dashboard</a>
        </div>

        <!-- Right: Notification & Profile -->
        <div class="flex items-center space-x-4">
            <!-- Notification Bell -->
            <div class="relative">
                <button @click="notifOpen = !notifOpen" class="relative text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(auth()->user()->unreadNotifications->count())
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full animate-ping"></span>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full"></span>
                    @endif
                </button>

                <!-- Dropdown -->
                <div x-show="notifOpen" @click.outside="notifOpen = false"
                     class="absolute right-0 mt-2 w-80 bg-white border rounded shadow-lg z-50"
                     x-cloak>
                    <div class="p-4 font-semibold border-b">Notifications</div>
                    <ul class="max-h-64 overflow-y-auto text-sm">
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            <li class="px-4 py-2 border-b hover:bg-gray-100">
                                {{ $notification->data['message'] ?? 'New Notification' }}
                            </li>
                        @empty
                            <li class="px-4 py-2 text-gray-500">No new notifications</li>
                        @endforelse
                    </ul>
                    @if(auth()->user()->unreadNotifications->count())
                        <form method="POST" action="{{ route('notifications.markAllRead') }}" class="p-2 text-center">
                            @csrf
                            <button class="text-blue-500 hover:underline text-sm">Mark all as read</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Username and Logout -->
            <span class="text-sm">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Optional Page Header -->
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-800">{{ $header }}</h1>
            </div>
        </header>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded shadow p-6">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-inner py-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} {{ config('app.name', 'Booking System') }}. All rights reserved.
    </footer>
</body>
</html>
