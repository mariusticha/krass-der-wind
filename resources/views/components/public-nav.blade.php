<nav class="border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                </svg>
                <span class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Krass der Wind</span>
            </a>

            <!-- Nav Links - Left Aligned -->
            <div class="flex items-center space-x-4 md:space-x-8 flex-1 ml-8">
                <a
                    href="{{ route('gigs.index') }}"
                    class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition text-sm md:text-base {{ request()->routeIs('gigs.*') ? 'font-semibold !text-amber-600 dark:!text-amber-500' : '' }}"
                >
                    Gigs
                </a>
                <a href="https://noethernetz.de/krassderwind/noten-fuer-krassderwind/" target="_blank" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition flex items-center gap-1 text-sm md:text-base">
                    Noten
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
                <a href="{{ route('home') }}#about" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition text-sm md:text-base">About</a>

                @if(app()->environment('local'))
                    <a href="{{ route('login') }}?auto_login=1" class="text-amber-600 dark:text-amber-500 hover:text-amber-700 dark:hover:text-amber-400 transition text-sm font-medium">
                        🔧 Quick Login
                    </a>
                @endif
            </div>

            <!-- Auth Links - Right Side -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User Dropdown Menu -->
                    <flux:dropdown position="bottom" align="end">
                        <flux:profile
                            :initials="auth()->user()->initials()"
                            icon-trailing="chevron-down"
                        />

                        <flux:menu>
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                            <flux:menu.separator />
                            <flux:menu.radio.group>
                                <flux:menu.item
                                    :href="route('dashboard')"
                                    icon="home"
                                    wire:navigate
                                    :class="request()->routeIs('dashboard') ? 'font-semibold' : ''"
                                >
                                    {{ __('Dashboard') }}
                                </flux:menu.item>
                                <flux:menu.item
                                    :href="route('profile.edit')"
                                    icon="cog"
                                    wire:navigate
                                    :class="request()->routeIs('profile.*', 'user-password.*', 'two-factor.*', 'appearance.*') ? 'font-semibold' : ''"
                                >
                                    {{ __('Settings') }}
                                </flux:menu.item>
                            </flux:menu.radio.group>
                            <flux:menu.separator />
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item
                                    as="button"
                                    type="submit"
                                    icon="arrow-right-start-on-rectangle"
                                    class="w-full cursor-pointer"
                                    data-test="logout-button"
                                >
                                    {{ __('Log Out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition text-sm md:text-base">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
