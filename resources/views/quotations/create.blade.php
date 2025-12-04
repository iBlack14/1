<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Nueva Cotizacion') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white/10 border border-white/20 rounded-2xl p-6 shadow-xl shadow-[#5F1BF2]/20 backdrop-blur">
                <p class="text-xs uppercase tracking-[0.25em] text-white/70">Cotizaciones</p>
                <h1 class="text-3xl font-bold text-white mt-2">Nueva Cotizacion</h1>
                <p class="text-white/80 text-sm mt-2">Completa los datos del cliente y arma tu propuesta con el esquema violeta-fucsia.</p>
            </div>

            <div class="relative">
                <div class="absolute -top-6 -left-10 h-32 w-32 bg-gradient-to-br from-[#5F1BF2] to-[#F2059F] blur-3xl opacity-40 pointer-events-none"></div>
                <div class="absolute -bottom-12 -right-6 h-36 w-36 bg-gradient-to-br from-[#8704BF] to-[#BF1F6A] blur-3xl opacity-35 pointer-events-none"></div>

                <div class="bg-white/90 backdrop-blur-xl shadow-2xl sm:rounded-3xl border border-white/50 relative">
                    <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#5F1BF2] via-[#8704BF] to-[#F2059F]"></div>
                    <div class="p-8 text-gray-800 space-y-8">
                        <form method="POST" action="{{ route('quotations.store') }}" x-data="quotationForm()">
                            @csrf

                            <!-- Client Data -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2 bg-white/60 border border-[#F2059F]/15 rounded-2xl p-6 shadow-inner shadow-white/40">
                            <div>
                                <x-input-label for="date" :value="__('Fecha')" class="text-gray-700" />
                                <x-text-input id="date" class="block mt-1 w-full bg-white border-gray-200 text-gray-900 focus:border-vc-magenta focus:ring-vc-magenta rounded-xl" type="date" name="date" :value="date('Y-m-d')" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="client_company" :value="__('Empresa / Razon Social')" class="text-gray-700" />
                                <x-text-input id="client_company" class="block mt-1 w-full bg-white border-gray-200 text-gray-900 placeholder-gray-400 focus:border-vc-magenta focus:ring-vc-magenta rounded-xl" type="text" name="client_company" />
                                <x-input-error :messages="$errors->get('client_company')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="client_ruc" :value="__('RUC')" class="text-gray-700" />
                                <x-text-input id="client_ruc" class="block mt-1 w-full bg-white border-gray-200 text-gray-900 placeholder-gray-400 focus:border-vc-magenta focus:ring-vc-magenta rounded-xl" type="text" name="client_ruc" />
                                <x-input-error :messages="$errors->get('client_ruc')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="client_phone" :value="__('Telefono')" class="text-gray-700" />
                                <x-text-input id="client_phone" class="block mt-1 w-full bg-white border-gray-200 text-gray-900 placeholder-gray-400 focus:border-vc-magenta focus:ring-vc-magenta rounded-xl" type="text" name="client_phone" />
                                <x-input-error :messages="$errors->get('client_phone')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="client_email" :value="__('Correo Electronico')" class="text-gray-700" />
                                <x-text-input id="client_email" class="block mt-1 w-full bg-white border-gray-200 text-gray-900 placeholder-gray-400 focus:border-vc-magenta focus:ring-vc-magenta rounded-xl" type="email" name="client_email" />
                                <x-input-error :messages="$errors->get('client_email')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="client_address" :value="__('Direccion')" class="text-gray-700" />
                                <x-text-input id="client_address" class="block mt-1 w-full bg-white border-gray-200 text-gray-900 placeholder-gray-400 focus:border-vc-magenta focus:ring-vc-magenta rounded-xl" type="text" name="client_address" />
                                <x-input-error :messages="$errors->get('client_address')" class="mt-2" />
                            </div>
                        </div>

                                                    <!-- Items -->
                            <div class="bg-white/80 rounded-2xl border border-[#5F1BF2]/15 shadow-md shadow-[#8704BF]/10">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 px-6 pt-6">Servicios / Productos</h3>
                                <div class="overflow-x-auto rounded-2xl border border-white/60">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gradient-to-r from-[#5F1BF2] via-[#8704BF] to-[#F2059F] text-white">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Servicio / Producto</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider w-24">Cantidad</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider w-32">Precio</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider w-32">Total</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider w-20 text-center">Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 bg-white/60">
                                            <template x-for="(item, index) in items" :key="index">
                                                <tr class="hover:text-white transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap overflow-visible relative">
                                                        <div x-data="{ 
                                                            isCustom: false, 
                                                            dropdownOpen: false,
                                                            dropdownStyle: '',
                                                            services: [
                                                                'WEB INFORMATIVA',
                                                                'WEB E-COMMERCE',
                                                                'WEB AULA VIRTUAL',
                                                                'POSICIONAMIENTO SEO',
                                                                'LICENCIA DE ANTIVIRUS',
                                                                'PLUGIN YOAST SEO',
                                                                'RESTRUCTURACION BASICA',
                                                                'RESTRUCTURACION E-COMMERCE',
                                                                'WEB FUSION E-COMMERCE',
                                                                'WEB FUSION AULA VIRTUAL'
                                                            ],
                                                            selectService(service) {
                                                                if(service === 'OTRO') {
                                                                    this.isCustom = true;
                                                                    item.service_name = '';
                                                                } else {
                                                                    item.service_name = service;
                                                                }
                                                                this.dropdownOpen = false;
                                                            },
                                                            updateDropdownPosition() {
                                                                const rect = this.$refs.trigger.getBoundingClientRect();
                                                                const top = rect.bottom + 50; // pequeño espacio bajo el campo
                                                                const left = rect.left - 350; // mover un poco a la izquierda
                                                                const width = rect.width + 00; // compensar el corrimiento
                                                                this.dropdownStyle = `top:${top}px; left:${left}px; width:${width}px;`;
                                                            }
                                                        }" x-init="isCustom = !services.includes(item.service_name) && item.service_name !== ''">
                                                            
                                                            <!-- Custom Dropdown -->
                                                            <div x-show="!isCustom" class="relative" @click.away="dropdownOpen = false">
                                                                <!-- Dropdown Button -->
                                                                <button type="button" x-ref="trigger" @click="dropdownOpen = !dropdownOpen; if(dropdownOpen) updateDropdownPosition()" class="block w-full bg-white border-2 border-[#F2059F] text-gray-900 focus:border-[#F2059F] focus:ring-2 focus:ring-[#F2059F] rounded-xl shadow-sm px-4 py-2.5 text-left flex justify-between items-center">
                                                                    <span x-text="item.service_name || 'Seleccione...'" :class="!item.service_name ? 'text-gray-400' : 'text-gray-900'"></span>
                                                                    <svg class="h-5 w-5 text-gray-400 transition-transform" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                    </svg>
                                                                </button>
                                                                                                                                <!-- Dropdown Menu -->
                                                                <div x-show="dropdownOpen"
                                                                     x-transition:enter="transition ease-out duration-100"
                                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                                     x-transition:leave="transition ease-in duration-75"
                                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                                     class="fixed z-[9999] bg-white border border-gray-200 rounded-xl shadow-lg"
                                                                     :style="dropdownStyle"
                                                                     style="display: none;">
                                                                    <div class="py-1 flex flex-col">
                                                                        <template x-for="service in services" :key="service">
                                                                            <button type="button" @click="selectService(service)" 
                                                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-900 hover:bg-[#790fd6] hover:text-white transition-colors whitespace-nowrap"
                                                                                    x-text="service">
                                                                            </button>
                                                                        </template>
                                                                        <button type="button" @click="selectService('OTRO')" 
                                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-900 hover:bg-[#790fd6] hover:text-white transition-colors border-t border-gray-200 whitespace-nowrap">
                                                                            OTRO (Especificar)
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Hidden input to submit the value -->
                                                                <input type="hidden" :name="'items['+index+'][service_name]'" x-model="item.service_name">
                                                            </div>
                                                            
                                                            <!-- Custom Input -->
                                                            <div x-show="isCustom" class="flex gap-2">
                                                                <input type="text" :name="'items['+index+'][service_name]'" x-model="item.service_name" placeholder="Descripcion del servicio" class="block w-full bg-white border-gray-200 text-gray-900 focus:border-vc-magenta focus:ring-vc-magenta rounded-xl shadow-sm">
                                                                <button type="button" @click="isCustom = false; item.service_name = ''" class="text-xs text-[#8704BF] hover:text-[#F2059F] underline whitespace-nowrap">Volver a lista</button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" min="1" class="block w-full bg-white border-gray-200 text-gray-900 focus:border-vc-magenta focus:ring-vc-magenta rounded-xl shadow-sm">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="number" :name="'items['+index+'][price]'" x-model="item.price" min="0" step="0.01" class="block w-full bg-white border-gray-200 text-gray-900 focus:border-vc-magenta focus:ring-vc-magenta rounded-xl shadow-sm">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                                        <span x-text="(item.quantity * item.price).toFixed(2)" class="text-gray-900 font-semibold"></span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <button type="button" @click="removeItem(index)" class="text-[#BF1F6A] hover:text-[#F2059F] transition-colors">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 border-t border-gray-100 bg-white/70">
                                                    <button type="button" @click="addItem()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#5F1BF2] to-[#F2059F] border-0 rounded-xl font-semibold text-xs text-white uppercase tracking-widest shadow-md shadow-[#8704BF]/20 hover:shadow-lg hover:shadow-[#8704BF]/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#BF1F6A] focus:ring-offset-white transition ease-in-out duration-150">
                                                        + Agregar Item
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Totals --><!-- Totals -->
                        <div class="mt-6 flex justify-end">
                            <div class="w-64 bg-white/50 p-4 rounded-lg border border-gray-200">
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium text-gray-600">Subtotal:</span>
                                    <span class="text-gray-800" x-text="subtotal.toFixed(2)"></span>
                                </div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="apply_igv" value="1" x-model="applyIgv" class="rounded border-gray-300 text-vc-magenta shadow-sm focus:ring-vc-magenta">
                                        <span class="ml-2 text-sm text-gray-600">IGV (18%)</span>
                                    </label>
                                    <span class="text-gray-800" x-text="igvAmount.toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                                    <span class="font-bold text-gray-800 text-lg">Total:</span>
                                    <span class="font-bold text-vc-magenta text-lg" x-text="total.toFixed(2)"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4 bg-vc-magenta hover:bg-fuchsia-600 focus:bg-fuchsia-600 active:bg-fuchsia-700 border-0 shadow-lg shadow-vc-magenta/30">
                                {{ __('Generar Cotizacion') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function quotationForm() {
            return {
                items: [
                    { service_name: '', quantity: 1, price: 0 }
                ],
                applyIgv: false,
                addItem() {
                    this.items.push({ service_name: '', quantity: 1, price: 0 });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                get subtotal() {
                    return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
                },
                get igvAmount() {
                    return this.applyIgv ? this.subtotal * 0.18 : 0;
                },
                get total() {
                    return this.subtotal + this.igvAmount;
                }
            }
        }
    </script>
</x-app-layout>
