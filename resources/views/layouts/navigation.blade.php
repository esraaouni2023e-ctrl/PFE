<nav x-data="{ open: false }" style="background: rgba(255,255,255,.8); backdrop-filter: blur(14px) saturate(180%); -webkit-backdrop-filter: blur(14px) saturate(180%); border-bottom: 1px solid rgba(226,232,240,.6); box-shadow: 0 1px 3px rgba(15,23,42,.03);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between" style="height: 64px;">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2" style="text-decoration: none;">
                        <img src="{{ asset('im1.jpg') }}" alt="CapAvenir" style="width: 38px; height: 38px; border-radius: 10px; object-fit: cover; mix-blend-mode: multiply; box-shadow: 0 2px 8px rgba(56,188,212,.15); transition: transform .3s ease;">
                        <span style="font-weight: 800; color: #0F1D32; font-size: 1.125rem; letter-spacing: -.02em;">CapAvenir</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::user()->isCounselor())
                        <x-nav-link :href="route('counselor.dashboard')" :active="request()->routeIs('counselor.dashboard')">
                            {{ __('Espace Accompagnement') }}
                        </x-nav-link>
                    @elseif(Auth::user()->isStudent())
                        <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                            {{ __('Tableau de Bord') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Accueil') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Administration') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings & Logout -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-transparent hover:text-gray-700 focus:outline-none transition ease-in-out duration-150" style="font-family: 'Inter', sans-serif;">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <!-- Direct Logout Button -->
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Déconnexion">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->isCounselor())
                <x-responsive-nav-link :href="route('counselor.dashboard')" :active="request()->routeIs('counselor.dashboard')">
                    {{ __('Espace Accompagnement') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->isStudent())
                <x-responsive-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                    {{ __('Tableau de Bord') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Accueil') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Administration') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
