<nav x-data="{ open: false }" class="bg-vc-purple rounded-b-[15px] shadow-lg border-b border-white/10 z-[100] relative">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> <!-- Increased height for premium feel -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <!-- White Logo -->
                        <!-- White Logo -->
                        <x-application-logo class="block h-16 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links + User -->
                <div class="hidden sm:flex sm:items-center sm:ms-10 space-x-8 sm:-my-px">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-gray-200 border-transparent hover:border-white">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('quotations.create')" :active="request()->routeIs('quotations.create')" class="text-white hover:text-gray-200 border-transparent hover:border-white">
                        {{ __('Nueva Cotización') }}
                    </x-nav-link>
                    <x-nav-link :href="route('quotations.index')" :active="request()->routeIs('quotations.index')" class="text-white hover:text-gray-200 border-transparent hover:border-white">
                        {{ __('Seguimiento') }}
                    </x-nav-link>
                    <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.index')" class="text-white hover:text-gray-200 border-transparent hover:border-white">
                        {{ __('Ajustes') }}
                    </x-nav-link>

                    <!-- Settings Dropdown (Administrador) -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
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
                </div>
            </div>

            <!-- Notification Center flotante y siempre visible -->
            <div class="hidden sm:flex sm:items-center sm:ms-6" x-data="{ 
                reminders: [],
                loading: true,
                init() {
                    fetch('{{ route('api.reminders') }}')
                        .then(res => res.json())
                        .then(data => {
                            this.reminders = data;
                            this.loading = false;
                        });
                }
            }">
                <!-- Panel flotante fijo al lado derecho, sin ícono -->
                <div class="fixed top-20 right-8 w-96 rounded-xl shadow-2xl py-1 bg-white ring-1 ring-black ring-opacity-5 border border-gray-100 z-[100] pointer-events-auto">
                        
                        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
                            <h3 class="text-sm font-bold text-gray-900">Notificaciones</h3>
                            <button class="text-xs text-[#BF1F6A] hover:text-[#8704BF] font-medium transition-colors">
                                Marcar todas como leídas
                            </button>
                        </div>

                        <div class="max-h-[400px] overflow-y-auto">
                            <template x-for="reminder in reminders" :key="reminder.id">
                                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition-colors duration-150">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 pt-0.5">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-[#BF1F6A] to-[#5F1BF2] flex items-center justify-center shadow-sm">
                                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 w-0 flex-1">
                                            <p class="text-sm font-bold text-gray-900" x-text="reminder.client"></p>
                                            <p class="text-xs text-gray-500 mt-0.5" x-text="reminder.reason"></p>
                                            <div class="mt-2 flex space-x-3">
                                                <a :href="`https://wa.me/${reminder.phone}`" target="_blank" class="text-xs font-bold text-green-600 hover:text-green-500 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                                    WhatsApp
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <a :href="reminder.link" class="text-xs font-bold text-[#BF1F6A] hover:text-[#8704BF]">
                                                    Ver Cotización
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="reminders.length === 0" class="px-4 py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay recordatorios para hoy.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-200 hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 backdrop-blur-xl rounded-b-[15px] shadow-xl">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('quotations.create')" :active="request()->routeIs('quotations.create')">
                {{ __('Nueva Cotización') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('quotations.index')" :active="request()->routeIs('quotations.index')">
                {{ __('Seguimiento') }}
            </x-responsive-nav-link>
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
