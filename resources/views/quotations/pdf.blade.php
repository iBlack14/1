<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Cotización {{ $quotation->client_company ?? $quotation->client_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: white;
            padding: 0;
        }

        .page {
            width: 100%;
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            position: relative;
            overflow: hidden;
        }

        /* Imágenes absolutas para header y footer */
        .header-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 1;
        }

        .footer-img {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
            padding: 70mm 20mm 50mm; /* Adjusted padding */
            box-sizing: border-box;
        }

        /* Sección datos del cliente */
        .client-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            color: #333;
        }

        .client-data {
            font-size: 13px;
            line-height: 1.8;
            color: #333;
        }

        .client-row {
            margin-bottom: 5px;
        }

        .client-label {
            display: inline-block;
            width: 80px;
            font-weight: normal;
        }

        .client-value {
            display: inline;
            font-weight: normal;
        }

        .fecha-row {
            margin-top: 15px;
            font-size: 13px;
            font-weight: bold;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }

        thead {
            background: #333;
            color: white;
        }

        th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            text-transform: capitalize;
            border: 1px solid #333;
        }

        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        tbody tr {
            background: #f9f9f9;
        }

        .amount {
            text-align: right;
        }

        /* Total */
        .total-section {
            text-align: right;
            margin: 20px 0;
            font-size: 14px;
            font-weight: bold;
            padding-right: 10px;
        }

        .total-label {
            display: inline-block;
            margin-right: 20px;
        }

        .total-value {
            display: inline-block;
            font-size: 16px;
            color: #333;
        }

        /* Extra Pages */
        .page--extra {
            page-break-before: always;
            margin: 0;
            width: 100%;
            height: 100%;
            position: relative;
        }

        .page--extra__image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
    </style>
</head>
<body>
    <div class="page">
        <img src="{{ public_path('images/cabezera.png') }}" class="header-img" alt="Header">
        <img src="{{ public_path('images/footer2.png') }}" class="footer-img" alt="Footer">

        <div class="content">
            <!-- Datos del Cliente -->
            <div class="client-section">
                <div class="section-title">Datos del Cliente</div>
                <div class="client-data">
                    <div class="client-row">
                        <span class="client-label">Empresa :</span>
                        <span class="client-value">{{ $quotation->client_company ?? $quotation->client_name }}</span>
                    </div>
                    @if($quotation->client_ruc)
                    <div class="client-row">
                        <span class="client-label">RUC :</span>
                        <span class="client-value">{{ $quotation->client_ruc }}</span>
                    </div>
                    @endif
                    @if($quotation->client_phone)
                    <div class="client-row">
                        <span class="client-label">Teléfono :</span>
                        <span class="client-value">{{ $quotation->client_phone }}</span>
                    </div>
                    @endif
                    @if($quotation->client_email)
                    <div class="client-row">
                        <span class="client-label">Correo :</span>
                        <span class="client-value">{{ $quotation->client_email }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Fecha -->
            <div class="fecha-row">
                Fecha: {{ \Carbon\Carbon::parse($quotation->date)->format('d/m/Y') }}
            </div>

            <!-- Tabla de Items -->
            <table>
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Cantidad</th>
                        <th class="amount">Precio</th>
                        <th class="amount">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $item)
                    <tr>
                        <td>{{ $item->service_name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td class="amount">{{ number_format($item->price, 2) }}</td>
                        <td class="amount">{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total -->
            <div class="total-section">
                <div style="margin-bottom: 5px;">
                    <span class="total-label">Subtotal:</span>
                    <span class="total-value">{{ number_format($quotation->subtotal, 2) }}</span>
                </div>
                @if($quotation->igv > 0)
                <div style="margin-bottom: 5px;">
                    <span class="total-label">IGV (18%):</span>
                    <span class="total-value">{{ number_format($quotation->igv, 2) }}</span>
                </div>
                @endif
                <div>
                    <span class="total-label" style="font-size: 18px;">Total:</span>
                    <span class="total-value" style="font-size: 18px; color: #4b1c91;">{{ number_format($quotation->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Logic for Extra Pages based on Services --}}
    @php
        // Mapeo antiguo como fallback
        $concept_image_map = [
            'WEB INFORMATIVA' => ['web-informativa.png'],
            'WEB E-COMMERCE' => ['E-COMERCE.png'],
            'WEB FUSION E-COMMERCE' => ['web-informativa.png', 'E-COMERCE.png'],
            'POSICIONAMIENTO SEO' => ['posicionamiento-seo.png'],
            'WEB AULA VIRTUAL' => ['aula-virtual.png'],
            'WEB FUSION AULA VIRTUAL' => ['web-informativa.png', 'aula-virtual.png'],
            'PLUGIN YOAST SEO' => ['yoast-seo.png'],
            'RESTRUCTURACIÓN BÁSICA' => ['restructuracion.png']
        ];

        $selected_images = [];
        foreach($quotation->items as $item) {
            // DEBUG: Ver qué image_path tenemos
            // dd(['service' => $item->service_name, 'image_path' => $item->image_path]);
            
            // Prioridad 1: Imagen personalizada seleccionada por el usuario
            if(!empty($item->image_path)) {
                $imageName = basename($item->image_path);
                if(!in_array($imageName, $selected_images)) {
                    $selected_images[] = $imageName;
                }
            } else {
                // Prioridad 2: Mapeo automático por nombre de servicio
                $service = strtoupper(trim($item->service_name));
                if(isset($concept_image_map[$service])) {
                    foreach($concept_image_map[$service] as $img) {
                        if(!in_array($img, $selected_images)) {
                            $selected_images[] = $img;
                        }
                    }
                }
            }
        }
    @endphp

    @foreach($selected_images as $image)
    <div class="page page--extra">
        <img class="page--extra__image" src="{{ public_path('images/' . $image) }}" alt="Detalle del servicio">
    </div>
    @endforeach
</body>
</html>
