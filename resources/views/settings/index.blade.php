<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ajustes del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'messages' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tabs Navigation -->
            <div class="flex space-x-1 rounded-xl bg-white/20 p-1 mb-6 backdrop-blur-sm">
                <button @click="activeTab = 'messages'"
                        :class="activeTab === 'messages' ? 'bg-white text-vc-purple shadow' : 'text-white hover:bg-white/10'"
                        class="w-full rounded-lg py-2.5 text-sm font-medium leading-5 ring-white ring-opacity-60 ring-offset-2 ring-offset-vc-purple focus:outline-none focus:ring-2 transition-all duration-200">
                    Mensajes y Plantillas
                </button>
                <button @click="activeTab = 'images'"
                        :class="activeTab === 'images' ? 'bg-white text-vc-purple shadow' : 'text-white hover:bg-white/10'"
                        class="w-full rounded-lg py-2.5 text-sm font-medium leading-5 ring-white ring-opacity-60 ring-offset-2 ring-offset-vc-purple focus:outline-none focus:ring-2 transition-all duration-200">
                    Imágenes de Servicios
                </button>
            </div>

            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-lg border border-white/50">
                <div class="p-8">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Tab: Messages -->
                    <div x-show="activeTab === 'messages'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <form method="POST" action="{{ route('settings.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900">Configuración de Mensajes</h3>
                                <p class="text-sm text-gray-500">Personaliza las plantillas para correos y WhatsApp.</p>
                            </div>

                            <!-- Variables Cheat Sheet -->
                            <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h3 class="text-sm font-bold text-gray-700 mb-2">Variables Disponibles:</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs text-gray-600">
                                    <div><span class="font-mono font-bold text-vc-magenta">[Nombre]</span> : Nombre Cliente</div>
                                    <div><span class="font-mono font-bold text-vc-magenta">[Empresa]</span> : Empresa</div>
                                    <div><span class="font-mono font-bold text-vc-magenta">[RUC]</span> : RUC</div>
                                    <div><span class="font-mono font-bold text-vc-magenta">[Fecha]</span> : Fecha Cotización</div>
                                    <div><span class="font-mono font-bold text-vc-magenta">[Servicio]</span> : Servicio Principal</div>
                                    <div><span class="font-mono font-bold text-vc-magenta">[Total]</span> : Monto Total</div>
                                    <div><span class="font-mono font-bold text-vc-magenta">[Link]</span> : Enlace PDF</div>
                                </div>
                            </div>

                            <div class="space-y-8">
                                <!-- Initial Quotation -->
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                    <h3 class="text-lg font-bold text-vc-purple mb-4 border-b pb-2">1. Cotización Inicial</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                            <textarea name="quotation_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['quotation_email_message'] ?? "Hola [Nombre],\n\nAdjunto encontrarás la cotización solicitada para el servicio de [Servicio].\n\nQuedamos atentos a tus comentarios.\n\nSaludos,\nVia Comunicativa" }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                            <textarea name="quotation_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['quotation_whatsapp_message'] ?? "Hola [Nombre], te adjunto la cotización para [Servicio]. Quedo atento." }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirmation -->
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                    <h3 class="text-lg font-bold text-vc-purple mb-4 border-b pb-2">2. Seguimiento: Confirmación</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                            <textarea name="confirmation_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['confirmation_email_message'] ?? "Hola [Nombre],\n\n¿Pudiste revisar la cotización enviada el [Fecha]?\n\nQuedo atento a tu confirmación para proceder.\n\nSaludos." }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                            <textarea name="confirmation_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['confirmation_whatsapp_message'] ?? "Hola [Nombre], ¿pudiste revisar la cotización? Quedo atento." }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Service -->
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                    <h3 class="text-lg font-bold text-vc-purple mb-4 border-b pb-2">3. Seguimiento: Servicio</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                            <textarea name="service_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['service_email_message'] ?? "Hola [Nombre],\n\nTe escribo para coordinar los detalles del servicio de [Servicio].\n\n¿Cuándo tendrías disponibilidad para una reunión?\n\nSaludos." }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                            <textarea name="service_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['service_whatsapp_message'] ?? "Hola [Nombre], coordinemos los detalles del servicio. ¿Tienes un momento?" }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Access -->
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                    <h3 class="text-lg font-bold text-vc-purple mb-4 border-b pb-2">4. Seguimiento: Accesos</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                            <textarea name="access_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['access_email_message'] ?? "Hola [Nombre],\n\nPara iniciar con el servicio, necesitamos que nos facilites los accesos correspondientes.\n\nQuedamos a la espera.\n\nSaludos." }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                            <textarea name="access_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['access_whatsapp_message'] ?? "Hola [Nombre], necesitamos los accesos para iniciar. ¿Podrías enviárnoslos?" }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resend -->
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                    <h3 class="text-lg font-bold text-vc-purple mb-4 border-b pb-2">5. Reenvío</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                            <textarea name="resend_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['resend_email_message'] ?? "Hola [Nombre],\n\nTe reenvío la cotización solicitada.\n\nCualquier duda quedo atento.\n\nSaludos." }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                            <textarea name="resend_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta text-sm text-gray-900">{{ $settings['resend_whatsapp_message'] ?? "Hola [Nombre], aquí tienes la cotización nuevamente." }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150" style="background-color: #a21caf !important; color: white !important;">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab: Service Images -->
                    <div x-show="activeTab === 'images'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                        
                        <!-- Upload Section -->
                        <div class="mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <h3 class="text-lg font-bold text-vc-purple mb-4">Subir Nueva Imagen de Servicio/Producto</h3>
                            <form action="{{ route('settings.images.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4 items-end">
                                @csrf
                                <div class="w-full sm:w-1/3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Descriptivo</label>
                                    <input type="text" name="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vc-magenta focus:ring-vc-magenta" placeholder="Ej: Diseño Web, Seo..." required>
                                </div>
                                <div class="w-full sm:w-1/3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen (JPG, PNG, WEBP)</label>
                                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" accept="image/*" required>
                                </div>
                                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-[#5F1BF2] to-[#F2059F] text-white rounded-lg font-medium hover:opacity-90">
                                    Subir Imagen
                                </button>
                            </form>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Image Gallery -->
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-4">Galería de Imágenes Disponibles</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    @forelse($images as $image)
                                        <div class="relative group bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all">
                                            <div class="h-24 overflow-hidden">
                                                <img src="{{ $image->url }}" alt="{{ $image->name }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="p-2">
                                                <p class="text-xs font-semibold text-gray-700 truncate" title="{{ $image->name }}">{{ $image->name }}</p>
                                            </div>
                                            <form action="{{ route('settings.images.delete', $image) }}" method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('¿Eliminar esta imagen? Se borrará de los servicios asignados.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white p-1 rounded-full hover:bg-red-600 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="col-span-full text-center py-8 text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                            No hay imágenes subidas.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Service Mappings -->
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-4">Asignar Imágenes a Servicios</h3>
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                    <!-- Add New Mapping Form -->
                                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                                        <form action="{{ route('settings.mappings.update') }}" method="POST" class="flex flex-col gap-3">
                                            @csrf
                                            <div>
                                                <label class="block text-xs font-bold text-[#8704BF] uppercase mb-1">Servicio del Dropdown</label>
                                                <select name="service_name" class="w-full text-sm rounded-lg border-[#8704BF]/30 text-[#4b1c91] font-medium bg-white focus:ring-[#F2059F] focus:border-[#F2059F] shadow-sm transition-colors" required>
                                                    <option value="" class="text-gray-400">Selecciona un servicio...</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service }}" class="text-[#4b1c91] font-medium">{{ $service }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-[#8704BF] uppercase mb-1">Imagen a Mostrar</label>
                                                <div class="relative">
                                                    <select name="service_image_id" class="w-full text-sm rounded-lg border-[#8704BF]/30 text-[#4b1c91] font-medium bg-white focus:ring-[#F2059F] focus:border-[#F2059F] shadow-sm transition-colors" required>
                                                        <option value="" class="text-gray-400">Selecciona una imagen...</option>
                                                        @foreach($images as $image)
                                                            <option value="{{ $image->id }}" class="text-[#4b1c91] font-medium">{{ $image->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" class="w-full px-4 py-2 bg-vc-magenta text-white text-sm font-medium rounded-lg hover:bg-fuchsia-700 transition-colors">
                                                + Asignar Imagen
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Mappings List -->
                                    <div class="max-h-[500px] overflow-y-auto">
                                        @if($mappings->count() > 0)
                                            <ul class="divide-y divide-gray-100">
                                                @foreach($mappings as $mapping)
                                                <li class="p-3 flex items-center justify-between hover:bg-gray-50 group">
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-10 w-10 rounded overflow-hidden border border-gray-200 bg-white shadow-sm flex-shrink-0">
                                                            @if($mapping->serviceImage)
                                                                <img src="{{ $mapping->serviceImage->url }}" class="h-full w-full object-cover">
                                                            @else
                                                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-xs">?</div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-800">{{ $mapping->service_name }}</p>
                                                            <p class="text-xs text-gray-500">{{ $mapping->serviceImage ? $mapping->serviceImage->name : 'Imagen eliminada' }}</p>
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('settings.mappings.delete', $mapping) }}" method="POST" onsubmit="return confirm('¿Quitar esta asignación?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-gray-400 hover:text-red-500 p-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="p-8 text-center text-gray-500 text-sm">
                                                No hay asignaciones configuradas.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 bg-blue-50 p-4 rounded-lg border border-blue-100 text-sm text-blue-800">
                            <h4 class="font-bold flex items-center gap-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                ¿Cómo funciona esto?
                            </h4>
                            <ul class="list-disc list-inside space-y-1 ml-1 opacity-90">
                                <li>Primero <strong>sube las imágenes</strong> que representan tus servicios (páginas extra del PDF).</li>
                                <li>Luego usa la sección de "Asignar Imágenes" para vincular un <strong>Servicio del Dropdown</strong> con una <strong>Imagen</strong>.</li>
                                <li>Cuando crees una cotización y selecciones ese servicio, las imágenes asignadas se añadirán automáticamente al PDF.</li>
                                <li>Puedes asignar <strong>múltiples imágenes</strong> al mismo servicio (se mostrarán en orden).</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
