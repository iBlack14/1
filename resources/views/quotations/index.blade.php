<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Seguimiento de Cotizaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-lg border border-white/50">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr style="background: linear-gradient(to right, #7e22ce, #c026d3); color: white;">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider rounded-tl-lg">Empresa</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Servicio Principal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha Envío</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha Respuesta</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Mensaje</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Reenvío</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider rounded-tr-lg">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100" x-data="{ activeId: null }">
                                @foreach($quotations as $quotation)
                                <tr class="hover:bg-gray-50 transition-colors duration-200" x-data="{ 
                                    responseDate: '{{ $quotation->response_date }}', 
                                    message: '{{ addslashes($quotation->follow_up_message) }}',
                                    note: '{{ addslashes($quotation->follow_up_note) }}',
                                    loading: false,
                                    update() {
                                        this.loading = true;
                                        fetch('{{ route('quotations.update', $quotation) }}', {
                                            method: 'PUT',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                response_date: this.responseDate,
                                                follow_up_message: this.message,
                                                follow_up_note: this.note
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            this.loading = false;
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            this.loading = false;
                                        });
                                    },
                                    sendEmail(mode) {
                                        if (!confirm('¿Estás seguro de enviar el correo?')) return;
                                        
                                        let type = this.message || 'initial';
                                        if (mode === 'resend') type = 'resend';

                                        this.loading = true;
                                        fetch('{{ route('quotations.send-email', $quotation) }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                email: '{{ $quotation->client_email }}',
                                                type: type
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            this.loading = false;
                                            alert('Correo enviado correctamente');
                                            if (mode === 'combined' || mode === 'resend') {
                                                this.openWhatsApp(mode);
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            this.loading = false;
                                            alert('Error al enviar el correo. Verifica que el cliente tenga un email válido.');
                                        });
                                    },
                                    openWhatsApp(mode = null) {
                                        let phone = '{{ $quotation->client_phone }}'.replace(/\D/g, '');
                                        if (!phone.startsWith('51') && phone.length === 9) {
                                            phone = '51' + phone;
                                        }
                                        
                                        let template = '';
                                        if (mode === 'resend') {
                                            template = `{{ $settings['resend_whatsapp_message'] ?? '' }}`;
                                        } else if (this.message === 'Confirmación') {
                                            template = `{{ $settings['confirmation_whatsapp_message'] ?? '' }}`;
                                        } else if (this.message === 'Servicio') {
                                            template = `{{ $settings['service_whatsapp_message'] ?? '' }}`;
                                        } else if (this.message === 'Acceso de su servicio') {
                                            template = `{{ $settings['access_whatsapp_message'] ?? '' }}`;
                                        } else {
                                            template = `{{ $settings['quotation_whatsapp_message'] ?? '' }}`;
                                        }

                                        // Fallbacks if settings are empty
                                        if (!template) {
                                            if (mode === 'resend') template = 'Hola [Nombre], te reenvío la cotización. Saludos.';
                                            else if (this.message === 'Confirmación') template = 'Hola [Nombre], ¿pudiste revisar la cotización?';
                                            else if (this.message === 'Servicio') template = 'Hola [Nombre], coordinemos el servicio.';
                                            else if (this.message === 'Acceso de su servicio') template = 'Hola [Nombre], necesitamos accesos.';
                                            else template = 'Hola [Nombre], adjunto cotización.';
                                        }

                                        // Replacements
                                        let messageText = template
                                            .replace(/\[Nombre\]/g, '{{ $quotation->client_name ?? $quotation->client_company }}')
                                            .replace(/\[Empresa\]/g, '{{ $quotation->client_company ?? "" }}')
                                            .replace(/\[RUC\]/g, '{{ $quotation->client_ruc ?? "" }}')
                                            .replace(/\[Fecha\]/g, '{{ \Carbon\Carbon::parse($quotation->date)->format("d/m/Y") }}')
                                            .replace(/\[Servicio\]/g, '{{ $quotation->items->first()->service_name ?? "" }}')
                                            .replace(/\[Total\]/g, '{{ number_format($quotation->total, 2) }}')
                                            .replace(/\[Link\]/g, '{{ route("quotations.download", $quotation) }}');

                                        let url = `https://wa.me/${phone}?text=${encodeURIComponent(messageText)}`;
                                        window.open(url, '_blank');
                                    }
                                }">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 relative">
                                        <div class="flex items-center space-x-2">
                                            <span>{{ $quotation->client_company ?? $quotation->client_name }}</span>
                                            <button @click="activeId = activeId === {{ $quotation->id }} ? null : {{ $quotation->id }}" 
                                                    class="text-gray-400 hover:text-fuchsia-600 focus:outline-none transition-colors"
                                                    :class="{ 'text-fuchsia-600': activeId === {{ $quotation->id }} }">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform duration-200" 
                                                     :class="{ 'rotate-180': activeId === {{ $quotation->id }} }"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div x-show="activeId === {{ $quotation->id }}" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 -translate-y-2"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 -translate-y-2"
                                             class="mt-3 w-full bg-gray-50 rounded-r-md border-l-4 border-fuchsia-600 shadow-sm p-4"
                                             style="display: none;">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-xs text-gray-600">
                                                <div class="col-span-1 sm:col-span-2 pb-1 border-b border-gray-200 mb-1 font-semibold text-fuchsia-700 uppercase tracking-wider">
                                                    Detalles del Cliente
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-gray-500 uppercase text-[10px]">Contacto</span>
                                                    <span class="text-gray-800 font-medium">{{ $quotation->client_name }}</span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-gray-500 uppercase text-[10px]">Email</span>
                                                    <span class="text-gray-800 truncate" title="{{ $quotation->client_email }}">{{ $quotation->client_email }}</span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-gray-500 uppercase text-[10px]">Teléfono</span>
                                                    <span class="text-gray-800">{{ $quotation->client_phone }}</span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-gray-500 uppercase text-[10px]">RUC</span>
                                                    <span class="text-gray-800">{{ $quotation->client_ruc }}</span>
                                                </div>
                                                <div class="col-span-1 sm:col-span-2 flex flex-col mt-1">
                                                    <span class="font-bold text-gray-500 uppercase text-[10px]">Dirección</span>
                                                    <span class="text-gray-800 truncate" title="{{ $quotation->client_address }}">{{ $quotation->client_address }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $quotation->items->first()->service_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="font-medium">{{ \Carbon\Carbon::parse($quotation->date)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $quotation->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex flex-col space-y-2 max-w-[150px]">
                                            <div class="relative group">
                                                <input type="date" 
                                                       x-model="responseDate" 
                                                       @change="update()" 
                                                       class="block w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-fuchsia-600 peer"
                                                       placeholder="dd/mm/aaaa">
                                            </div>
                                            <textarea x-model="note" 
                                                      @change="update()" 
                                                      rows="2" 
                                                      class="w-full text-xs rounded-md border-gray-200 focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm resize-none" 
                                                      placeholder="Nota recordatorio..."></textarea>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <select x-model="message" @change="update()" class="w-full text-sm rounded-md border-gray-300 focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm">
                                            <option value="">Seleccionar...</option>
                                            <option value="Confirmación">Confirmación</option>
                                            <option value="Servicio">Servicio</option>
                                            <option value="Acceso de su servicio">Acceso de su servicio</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="flex flex-row space-x-2">
                                            <button @click="$dispatch('open-preview', { url: '{{ route('quotations.show', $quotation) }}?clean=1' })" class="inline-flex items-center justify-center px-3 py-1 text-white rounded-md transition-colors text-xs font-medium shadow-sm" style="background-color: #c026d3; /* Magenta/Fuchsia-600 */">
                                                Ver Cotización
                                            </button>
                                            <button @click="sendEmail('resend')" class="inline-flex items-center justify-center px-3 py-1 text-white rounded-md transition-colors text-xs font-medium shadow-sm" style="background-color: #be185d; /* Pink-700 */">
                                                Reenviar
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-3">
                                            <!-- Email Button -->
                                            <button @click="sendEmail('email')" class="text-gray-400 hover:text-fuchsia-600 transition-colors p-2 rounded-full hover:bg-fuchsia-50" title="Enviar Email">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                                </svg>
                                            </button>

                                            <!-- WhatsApp Button -->
                                            <button @click="openWhatsApp()" class="text-gray-400 hover:text-green-600 transition-colors p-2 rounded-full hover:bg-green-50" title="Enviar WhatsApp">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.463 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                </svg>
                                            </button>

                                            <!-- Combined Button -->
                                            <button @click="sendEmail('combined')" class="text-gray-400 hover:text-purple-700 transition-colors p-2 rounded-full hover:bg-purple-50" title="Email + WhatsApp">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.126A59.768 59.768 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.876L5.999 12Zm0 0h7.5" />
                                                    </svg>
                                                </div>
                                            </button>
                                            
                                            <!-- Download PDF Button -->
                                            <a href="{{ route('quotations.download', $quotation) }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors p-2 rounded-full hover:bg-blue-50" title="Descargar PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                            </a>

                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('quotations.destroy', $quotation) }}" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar esta cotización?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-2 rounded-full hover:bg-red-50" title="Eliminar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
    <div x-data="{ open: false, src: '' }" 
         x-init="$watch('open', value => document.body.style.overflow = value ? 'hidden' : '')"
         @open-preview.window="open = true; src = $event.detail.url" 
         x-show="open" 
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto no-scrollbar" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="open = false"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle" style="width: 650px;">
                
                <div class="bg-white p-0">
                    <div class="flex justify-between items-center p-2 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Vista Previa</h3>
                        <button @click="open = false" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div>
                        <iframe :src="src" class="w-full border-0" style="min-height: 1400px;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
