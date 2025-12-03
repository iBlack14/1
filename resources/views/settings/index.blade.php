<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-fuchsia-700 leading-tight">
            {{ __('Ajustes de Mensajes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-lg border border-white/50">
                <div class="p-8">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Configuración de Mensajes</h3>
                        </div>

                        <!-- Variables Cheat Sheet -->
                        <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-sm font-bold text-gray-700 mb-2">Variables Disponibles:</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs text-gray-600">
                                <div><span class="font-mono font-bold text-fuchsia-600">[Nombre]</span> : Nombre Cliente</div>
                                <div><span class="font-mono font-bold text-fuchsia-600">[Empresa]</span> : Empresa</div>
                                <div><span class="font-mono font-bold text-fuchsia-600">[RUC]</span> : RUC</div>
                                <div><span class="font-mono font-bold text-fuchsia-600">[Fecha]</span> : Fecha Cotización</div>
                                <div><span class="font-mono font-bold text-fuchsia-600">[Servicio]</span> : Servicio Principal</div>
                                <div><span class="font-mono font-bold text-fuchsia-600">[Total]</span> : Monto Total</div>
                                <div><span class="font-mono font-bold text-fuchsia-600">[Link]</span> : Enlace PDF</div>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <!-- Initial Quotation -->
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                <h3 class="text-lg font-bold text-fuchsia-700 mb-4 border-b pb-2">1. Cotización Inicial</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                        <textarea name="quotation_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['quotation_email_message'] ?? "Hola [Nombre],\n\nAdjunto encontrarás la cotización solicitada para el servicio de [Servicio].\n\nQuedamos atentos a tus comentarios.\n\nSaludos,\nVia Comunicativa" }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                        <textarea name="quotation_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['quotation_whatsapp_message'] ?? "Hola [Nombre], te adjunto la cotización para [Servicio]. Quedo atento." }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmation -->
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                <h3 class="text-lg font-bold text-fuchsia-700 mb-4 border-b pb-2">2. Seguimiento: Confirmación</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                        <textarea name="confirmation_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['confirmation_email_message'] ?? "Hola [Nombre],\n\n¿Pudiste revisar la cotización enviada el [Fecha]?\n\nQuedo atento a tu confirmación para proceder.\n\nSaludos." }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                        <textarea name="confirmation_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['confirmation_whatsapp_message'] ?? "Hola [Nombre], ¿pudiste revisar la cotización? Quedo atento." }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Service -->
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                <h3 class="text-lg font-bold text-fuchsia-700 mb-4 border-b pb-2">3. Seguimiento: Servicio</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                        <textarea name="service_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['service_email_message'] ?? "Hola [Nombre],\n\nTe escribo para coordinar los detalles del servicio de [Servicio].\n\n¿Cuándo tendrías disponibilidad para una reunión?\n\nSaludos." }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                        <textarea name="service_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['service_whatsapp_message'] ?? "Hola [Nombre], coordinemos los detalles del servicio. ¿Tienes un momento?" }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Access -->
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                <h3 class="text-lg font-bold text-fuchsia-700 mb-4 border-b pb-2">4. Seguimiento: Accesos</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                        <textarea name="access_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['access_email_message'] ?? "Hola [Nombre],\n\nPara iniciar con el servicio, necesitamos que nos facilites los accesos correspondientes.\n\nQuedamos a la espera.\n\nSaludos." }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                        <textarea name="access_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['access_whatsapp_message'] ?? "Hola [Nombre], necesitamos los accesos para iniciar. ¿Podrías enviárnoslos?" }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Resend -->
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                <h3 class="text-lg font-bold text-fuchsia-700 mb-4 border-b pb-2">5. Reenvío</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para Email</label>
                                        <textarea name="resend_email_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['resend_email_message'] ?? "Hola [Nombre],\n\nTe reenvío la cotización solicitada.\n\nCualquier duda quedo atento.\n\nSaludos." }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje para WhatsApp</label>
                                        <textarea name="resend_whatsapp_message" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 text-sm">{{ $settings['resend_whatsapp_message'] ?? "Hola [Nombre], aquí tienes la cotización nuevamente." }}</textarea>
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
            </div>
        </div>
    </div>
</x-app-layout>
