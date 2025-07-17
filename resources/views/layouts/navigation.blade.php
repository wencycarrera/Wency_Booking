<nav x-data="{ open: false, sidebarOpen: false }" class="bg-white border-b border-gray-100 relative z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Hamburger Icon (Mobile Only) -->
                <button @click="sidebarOpen = !sidebarOpen" class="sm:hidden mr-3 text-gray-600 hover:text-gray-900 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-6 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')">
                        {{ __('Booking') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-4">
                <!-- Notification Bell -->
                <div class="relative">
                    <button id="notifToggle" class="relative text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(auth()->user()->unreadNotifications->count())
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full animate-ping"></span>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full"></span>
                        @endif
                    </button>
                    <!-- Notification Dropdown -->
                    <div id="notifDropdown"
                        class="hidden absolute right-0 mt-2 w-80 bg-white border rounded shadow-lg z-50">
                        <div class="p-4 font-semibold border-b">Notifications</div>
                        <ul class="max-h-64 overflow-y-auto">
                            @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                <li class="px-4 py-2 text-sm text-gray-700 border-b hover:bg-gray-100">
                                    {{ $notification->data['message'] ?? 'New Notification' }}
                                </li>
                            @empty
                                <li class="px-4 py-2 text-sm text-gray-500">No new notifications</li>
                            @endforelse
                        </ul>
                        @if(auth()->user()->unreadNotifications->count())
                            <form method="POST" action="{{ route('notifications.markAllRead') }}" class="p-2 text-center">
                                @csrf
                                <button type="submit" class="text-blue-500 text-sm hover:underline">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Profile -->
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 
                                        10.586l3.293-3.293a1 1 0 
                                        011.414 1.414l-4 4a1 1 0 
                                        01-1.414 0l-4-4a1 1 0 
                                        010-1.414z" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Menu -->
    <div x-show="sidebarOpen" @click.away="sidebarOpen = false"
         class="sm:hidden absolute left-0 top-16 w-64 bg-white border-r border-gray-200 shadow-md z-30"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 -translate-x-full"
         style="display: none;">
        <nav class="px-4 py-4 space-y-2">
            <a href="{{ route('bookings.create') }}" class="block px-3 py-2 rounded hover:bg-purple-100 text-gray-700">
                ➕ Create Booking
            </a>
            <a href="{{ route('bookings.index') }}" class="block px-3 py-2 rounded hover:bg-purple-100 text-gray-700">
                📋 View Bookings
            </a>
            <a href="{{ route('users.index') }}" class="block px-3 py-2 rounded hover:bg-purple-100 text-gray-700">
                👤 User Management
            </a>
        </nav>
    </div>

    <!-- Dropdown Toggle Script -->
    <script>
        document.getElementById('notifToggle')?.addEventListener('click', function () {
            document.getElementById('notifDropdown')?.classList.toggle('hidden');
        });
    </script>
</nav>
