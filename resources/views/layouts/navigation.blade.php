<nav x-data="{ open: false }" class="bg-white/75 backdrop-blur-xl border-b border-slate-200/70 sticky top-0 z-50 shadow-[0_1px_0_rgba(15,23,42,0.04)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-10">
                <a href="{{ route('dashboard') }}" class="group shrink-0 flex items-center gap-3">
                    <div class="w-11 h-11 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-lg shadow-lg shadow-indigo-200 group-hover:shadow-xl transform group-hover:scale-105 transition-all">
                        V
                    </div>
                    <div>
                        <div class="font-extrabold text-xl tracking-tight text-slate-900">Vacaciones</div>
                        <div class="text-[11px] uppercase tracking-[0.24em] text-slate-400 font-semibold">Vacation control</div>
                    </div>
                </a>

                <div class="hidden sm:flex items-center gap-2 p-1.5 rounded-2xl bg-slate-100/70 border border-slate-200/70">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl px-4 py-2 font-semibold text-sm transition-colors {{ request()->routeIs('dashboard') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('vacations.index')" :active="request()->routeIs('vacations.*')" class="rounded-xl px-4 py-2 font-semibold text-sm transition-colors {{ request()->routeIs('vacations.*') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                        {{ __('Vacaciones') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supervisor')
                        <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" class="rounded-xl px-4 py-2 font-semibold text-sm transition-colors {{ request()->routeIs('employees.*') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            {{ __('Empleados') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center gap-3 rounded-2xl border border-slate-200/70 bg-white px-4 py-2 shadow-sm">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-slate-900 to-slate-700 text-white flex items-center justify-center text-xs font-black uppercase">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div class="leading-tight">
                        <div class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</div>
                        <div class="text-[11px] uppercase tracking-[0.2em] text-slate-400 font-semibold">{{ Auth::user()->role }}</div>
                    </div>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center justify-center h-9 w-9 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-200/70 bg-white/90 backdrop-blur-xl">
        <div class="pt-3 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('vacations.index')" :active="request()->routeIs('vacations.*')">{{ __('Vacaciones') }}</x-responsive-nav-link>
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supervisor')
                <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">{{ __('Empleados') }}</x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-slate-200/70">
            <div class="px-4">
                <div class="font-bold text-base text-slate-900">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 px-4 pb-3">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
