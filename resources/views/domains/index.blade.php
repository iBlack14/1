<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Panel de Dominios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Dominios -->
                <div class="bg-gradient-to-br from-[#5F1BF2] to-[#8704BF] rounded-2xl p-6 text-white shadow-xl shadow-[#5F1BF2]/30 backdrop-blur transform hover:scale-105 transition-transform duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-white/80 text-sm font-medium uppercase tracking-wide">Total Dominios</p>
                            <h3 class="text-4xl font-bold mt-2">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Activos -->
                <div class="bg-gradient-to-br from-[#10b981] to-[#059669] rounded-2xl p-6 text-white shadow-xl shadow-green-500/30 backdrop-blur transform hover:scale-105 transition-transform duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-white/80 text-sm font-medium uppercase tracking-wide">Activos</p>
                            <h3 class="text-4xl font-bold mt-2">{{ $stats['active'] }}</h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Por Vencer -->
                <div class="bg-gradient-to-br from-[#f59e0b] to-[#d97706] rounded-2xl p-6 text-white shadow-xl shadow-amber-500/30 backdrop-blur transform hover:scale-105 transition-transform duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-white/80 text-sm font-medium uppercase tracking-wide">Por Vencer</p>
                            <h3 class="text-4xl font-bold mt-2">{{ $stats['expiring'] }}</h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Expirados -->
                <div class="bg-gradient-to-br from-[#ef4444] to-[#dc2626] rounded-2xl p-6 text-white shadow-xl shadow-red-500/30 backdrop-blur transform hover:scale-105 transition-transform duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-white/80 text-sm font-medium uppercase tracking-wide">Expirados</p>
                            <h3 class="text-4xl font-bold mt-2">{{ $stats['expired'] }}</h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-white/50">
                <form method="GET" action="{{ route('domains.index') }}" class="space-y-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Search Bar -->
                        <div class="flex-1">
                            <div class="relative flex items-center">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por dominio, cliente..." class="block w-full pl-4 pr-12 py-3.5 border-2 border-[#F2059F] rounded-xl focus:ring-2 focus:ring-[#8704BF] focus:border-[#8704BF] bg-white text-gray-900 placeholder-gray-400 shadow-sm text-base">
                                <div class="absolute right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-6 w-6 text-[#8704BF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex gap-2">
                            <button type="submit" name="status" value="" class="px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ !request('status') ? 'bg-gradient-to-r from-[#5F1BF2] to-[#F2059F] text-white shadow-lg' : 'bg-white text-gray-700 border-2 border-gray-200 hover:border-[#8704BF]' }}">
                                Todos
                            </button>
                            <button type="submit" name="status" value="activo" class="px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request('status') === 'activo' ? 'bg-gradient-to-r from-[#5F1BF2] to-[#F2059F] text-white shadow-lg' : 'bg-white text-gray-700 border-2 border-gray-200 hover:border-[#8704BF]' }}">
                                Activos
                            </button>
                            <button type="submit" name="status" value="por_vencer" class="px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request('status') === 'por_vencer' ? 'bg-gradient-to-r from-[#5F1BF2] to-[#F2059F] text-white shadow-lg' : 'bg-white text-gray-700 border-2 border-gray-200 hover:border-[#8704BF]' }}">
                                Por Vencer
                            </button>
                            <button type="submit" name="status" value="expirado" class="px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request('status') === 'expirado' ? 'bg-gradient-to-r from-[#5F1BF2] to-[#F2059F] text-white shadow-lg' : 'bg-white text-gray-700 border-2 border-gray-200 hover:border-[#8704BF]' }}">
                                Expirados
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Domain Cards Grid -->
            @if($domains->count() > 0)
                <div class="grid grid-cols-1 gap-6">
                    @foreach($domains as $domain)
                        <div x-data="{ expanded: false }" class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border-2 border-white/50 overflow-hidden hover:shadow-2xl hover:border-[#8704BF] transition-all duration-300">
                            <!-- Card Header -->
                            <div class="bg-gradient-to-r from-[#5F1BF2] via-[#8704BF] to-[#F2059F] p-6">
                                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-2xl font-bold text-white break-words mb-2">{{ $domain->domain_name }}</h3>
                                        <p class="text-white/80 text-sm">Cliente: <span class="font-semibold">{{ $domain->user->name }}</span></p>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        @php
                                            $statusColors = [
                                                'activo' => 'bg-green-500',
                                                'expirado' => 'bg-red-500',
                                                'pendiente' => 'bg-yellow-500',
                                                'suspendido' => 'bg-gray-500'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold text-white {{ $statusColors[$domain->status] ?? 'bg-gray-500' }} uppercase">
                                            {{ $domain->status }}
                                        </span>
                                        
                                        <!-- Action Buttons -->
                                        <a href="{{ route('domains.edit', $domain) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-white text-xs font-semibold uppercase tracking-wider transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editar
                                        </a>
                                        <form action="{{ route('domains.destroy', $domain) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este dominio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500/90 hover:bg-red-600 rounded-lg text-white text-xs font-semibold uppercase tracking-wider transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                        
                                        <button @click="expanded = !expanded" type="button" class="text-white hover:bg-white/20 rounded-lg p-1.5 transition-colors">
                                            <svg class="w-5 h-5 transform transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body (Collapsible) -->
                            <div x-show="expanded" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="p-6">
                                
                                <!-- Mini Cards - Additional Services -->
                                <div>
                                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-4">Información del Servicio</h4>
                                    <div class="flex gap-4 overflow-x-auto pb-2">
                                        <!-- Cliente -->
                                        <div class="bg-gradient-to-br from-[#5F1BF2] to-[#8704BF] rounded-xl p-5 min-w-[200px] flex-1 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-white/20">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="bg-white/20 rounded-lg p-2">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-white/90 uppercase tracking-wide">Cliente</span>
                                            </div>
                                            <p class="text-base font-semibold text-white truncate">{{ $domain->user->name }}</p>
                                        </div>

                                        <!-- Fecha de Activación -->
                                        <div class="bg-gradient-to-br from-[#8704BF] to-[#BF1F6A] rounded-xl p-5 min-w-[200px] flex-1 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-white/20">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="bg-white/20 rounded-lg p-2">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-white/90 uppercase tracking-wide">Activación</span>
                                            </div>
                                            <p class="text-base font-semibold text-white">{{ $domain->registration_date->format('d/m/Y') }}</p>
                                        </div>

                                        <!-- Fecha de Vencimiento -->
                                        <div class="bg-gradient-to-br from-[#BF1F6A] to-[#F2059F] rounded-xl p-5 min-w-[200px] flex-1 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-white/20">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="bg-white/20 rounded-lg p-2">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-white/90 uppercase tracking-wide">Vencimiento</span>
                                            </div>
                                            @php
                                                $days = $domain->daysUntilExpiration();
                                            @endphp
                                            <p class="text-base font-semibold text-white">
                                                @if($days >= 0)
                                                    {{ $days }} días
                                                    @if($domain->isExpiringSoon())
                                                        <span class="inline-block ml-1 w-2 h-2 bg-yellow-300 rounded-full animate-pulse"></span>
                                                    @endif
                                                @else
                                                    <span class="flex items-center gap-1">
                                                        Expirado
                                                        <span class="inline-block w-2 h-2 bg-red-400 rounded-full animate-pulse"></span>
                                                    </span>
                                                @endif
                                            </p>
                                        </div>

                                        <!-- Precio -->
                                        <div class="bg-gradient-to-br from-[#F2059F] to-[#BF1F6A] rounded-xl p-5 min-w-[200px] flex-1 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-white/20">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="bg-white/20 rounded-lg p-2">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-white/90 uppercase tracking-wide">Precio</span>
                                            </div>
                                            <p class="text-base font-bold text-white">S/ {{ number_format($domain->price, 2) }}</p>
                                        </div>

                                        <!-- Licencias -->
                                        <div class="bg-gradient-to-br from-[#8704BF] to-[#5F1BF2] rounded-xl p-5 min-w-[200px] flex-1 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-white/20">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="bg-white/20 rounded-lg p-2">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-white/90 uppercase tracking-wide">Licencias</span>
                                            </div>
                                            <p class="text-base font-semibold text-white truncate" title="{{ $domain->licenses ?? 'No configurado' }}">
                                                @if($domain->licenses)
                                                    {{ \Illuminate\Support\Str::limit($domain->licenses, 25) }}
                                                    <span class="inline-block w-2 h-2 bg-green-400 rounded-full animate-pulse ml-1"></span>
                                                @else
                                                    No configurado
                                                @endif
                                            </p>
                                        </div>

                                        <!-- Plugins -->
                                        <div class="bg-gradient-to-br from-[#BF1F6A] to-[#8704BF] rounded-xl p-5 min-w-[200px] flex-1 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-white/20">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="bg-white/20 rounded-lg p-2">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-white/90 uppercase tracking-wide">Plugins</span>
                                            </div>
                                            <p class="text-base font-semibold text-white/80 truncate" title="{{ $domain->plugins ?? 'No configurado' }}">
                                                {{ $domain->plugins ? \Illuminate\Support\Str::limit($domain->plugins, 25) : 'No configurado' }}
                                            </p>
                                        </div>

                                        <!-- Mantenimiento -->
                                        <div class="bg-gradient-to-br from-[#F2059F] to-[#5F1BF2] rounded-xl p-5 min-w-[220px] flex-1 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-white/20">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="bg-white/20 rounded-lg p-2">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-white/90 uppercase tracking-wide">Mantenimiento</span>
                                            </div>
                                            <p class="text-base font-semibold text-white flex items-center gap-2">
                                                {{ ucfirst($domain->maintenance_status) }}
                                                @if($domain->maintenance_status === 'activo')
                                                    <span class="inline-block w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $domains->links() }}
                </div>
            @else
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl p-12 text-center border border-white/50">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">No hay dominios registrados</h3>
                    <p class="mt-2 text-gray-500">Comienza agregando tu primer dominio</p>
                    <div class="mt-6">
                        <a href="{{ route('domains.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#5F1BF2] to-[#F2059F] border-0 rounded-xl font-semibold text-sm text-white uppercase tracking-widest shadow-lg shadow-[#8704BF]/30 hover:shadow-xl hover:shadow-[#8704BF]/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#BF1F6A] transition-all duration-150">
                            + Agregar Dominio
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Floating Action Button -->
    <a href="{{ route('domains.create') }}" class="fixed bottom-8 right-8 bg-gradient-to-r from-[#5F1BF2] to-[#F2059F] text-white p-5 rounded-full shadow-2xl shadow-[#8704BF]/50 hover:shadow-3xl hover:scale-110 focus:outline-none focus:ring-4 focus:ring-[#8704BF]/50 transition-all duration-200 z-50 group">
        <svg class="w-8 h-8 transform group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </a>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-4 right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
</x-app-layout>
