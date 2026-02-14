<nav class="backdrop-blur-xl fixed top-0 left-0 right-0 z-50 transition-all duration-700 ease-out"
    :class="isWelcomePage ? (scrolled ? 'translate-y-0 opacity-100' : '-translate-y-full opacity-0') :
        'translate-y-0 opacity-100'"
    x-data="{ mobileMenuOpen: false }" x-cloak>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                </svg>
                <span class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Krass der Wind</span>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex items-center space-x-8 flex-1 ml-8">
                <a href="{{ route('gigs.index') }}"
                    class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition text-base {{ request()->routeIs('gigs.*') ? 'font-semibold !text-amber-600 dark:!text-amber-500' : '' }}">
                    Gigs
                </a>
                <a href="{{ route('songs.index') }}"
                    class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition text-base {{ request()->routeIs('songs.*') ? 'font-semibold !text-amber-600 dark:!text-amber-500' : '' }}">
                    Songs
                </a>
                <a href="https://noethernetz.de/krassderwind/noten-fuer-krassderwind/" target="_blank"
                    class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition flex items-center gap-1 text-base">
                    Noten
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
                <a href="{{ route('home') }}#about"
                    class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition text-base">About</a>

                @if (app()->environment('local'))
                    <a href="{{ route('login') }}?auto_login=1"
                        class="text-amber-600 dark:text-amber-500 hover:text-amber-700 dark:hover:text-amber-400 transition text-sm font-medium">
                        🔧 Quick Login
                    </a>
                @endif
            </div>

            <!-- Desktop Auth Links -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <x-ui.user-menu />
                @else
                    <a href="{{ route('login') }}"
                        class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition text-base flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden p-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden pb-4">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('gigs.index') }}"
                    class="px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition {{ request()->routeIs('gigs.*') ? 'font-semibold !text-amber-600 dark:!text-amber-500 bg-amber-50 dark:bg-amber-950/30' : '' }}"
                    @click="mobileMenuOpen = false">
                    Gigs
                </a>
                <a href="{{ route('songs.index') }}"
                    class="px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition {{ request()->routeIs('songs.*') ? 'font-semibold !text-amber-600 dark:!text-amber-500 bg-amber-50 dark:bg-amber-950/30' : '' }}"
                    @click="mobileMenuOpen = false">
                    Songs
                </a>
                <a href="https://noethernetz.de/krassderwind/noten-fuer-krassderwind/" target="_blank"
                    class="px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition flex items-center gap-1"
                    @click="mobileMenuOpen = false">
                    Noten
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
                <a href="{{ route('home') }}#about"
                    class="px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition"
                    @click="mobileMenuOpen = false">
                    About
                </a>

                @if (app()->environment('local'))
                    <a href="{{ route('login') }}?auto_login=1"
                        class="px-3 py-2 rounded-lg text-amber-600 dark:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition font-medium"
                        @click="mobileMenuOpen = false">
                        🔧 Quick Login
                    </a>
                @endif

                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-3 mt-3">
                    @auth
                        <div class="px-3 py-2 mb-2">
                            <div class="flex items-center gap-2">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()"
                                    size="sm" />
                                <div class="text-sm">
                                    <div class="font-semibold text-zinc-900 dark:text-zinc-100">{{ auth()->user()->name }}
                                    </div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('dashboard') }}"
                            class="px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'font-semibold' : '' }}"
                            @click="mobileMenuOpen = false">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition flex items-center gap-2 {{ request()->routeIs('profile.*', 'user-password.*', 'two-factor.*', 'appearance.*') ? 'font-semibold' : '' }}"
                            @click="mobileMenuOpen = false">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-3 py-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition flex items-center gap-2"
                            @click="mobileMenuOpen = false">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
