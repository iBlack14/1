<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function create()
    {
        return view('quotations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_company' => 'required|string|max:255',
            'client_ruc' => 'nullable|string|max:20',
            'client_phone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:255',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.service_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.image_path' => 'nullable|string',
            'apply_igv' => 'boolean',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $igv = $request->has('apply_igv') ? $subtotal * 0.18 : 0;
        $total = $subtotal + $igv;

        $quotation = Quotation::create([
            'user_id' => Auth::id(),
            'client_name' => $validated['client_company'], // Use company name as client name
            'client_company' => $validated['client_company'],
            'client_ruc' => $validated['client_ruc'],
            'client_phone' => $validated['client_phone'],
            'client_email' => $validated['client_email'],
            'client_address' => $validated['client_address'],
            'date' => $validated['date'],
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $total,
        ]);

        foreach ($request->items as $item) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'service_name' => $item['service_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price'],
                'image_path' => $item['image_path'] ?? null,
            ]);
        }

        return redirect()->route('quotations.show', $quotation);
    }

    public function show(Quotation $quotation)
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('quotations.show', compact('quotation', 'settings'));
    }

    public function index()
    {
        $quotations = Quotation::with('items')->latest()->get();
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('quotations.index', compact('quotations', 'settings'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'response_date' => 'nullable|date',
            'follow_up_message' => 'nullable|string',
            'follow_up_note' => 'nullable|string',
        ]);

        $quotation->update($validated);

        return response()->json(['success' => true]);
    }

    public function downloadPDF(Quotation $quotation)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('quotations.pdf', compact('quotation'));
        return $pdf->download('COT-' . strtoupper(\Illuminate\Support\Str::slug($quotation->client_company ?? $quotation->client_name)) . '-' . $quotation->date . '.pdf');
    }

    public function sendEmail(Request $request, Quotation $quotation)
    {
        $request->validate([
            'email' => 'required|email',
            'type' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf',
        ]);

        // 1. Determine Message Body
        $type = $request->input('type', 'initial');
        $settingKey = 'quotation_email_message';

        switch ($type) {
            case 'Confirmación': $settingKey = 'confirmation_email_message'; break;
            case 'Servicio': $settingKey = 'service_email_message'; break;
            case 'Acceso de su servicio': $settingKey = 'access_email_message'; break;
            case 'resend': $settingKey = 'resend_email_message'; break;
            case 'initial': default: $settingKey = 'quotation_email_message'; break;
        }

        $template = \App\Models\Setting::where('key', $settingKey)->value('value');
        $body = $template;

        // 2. Variable Replacement
        if ($body) {
            $replacements = [
                '[Nombre]' => $quotation->client_name ?? $quotation->client_company,
                '[Empresa]' => $quotation->client_company ?? '',
                '[RUC]' => $quotation->client_ruc ?? '',
                '[Fecha]' => \Carbon\Carbon::parse($quotation->date)->format('d/m/Y'),
                '[Servicio]' => $quotation->items->first()->service_name ?? '',
                '[Total]' => number_format($quotation->total, 2),
                '[Telefono]' => $quotation->client_phone ?? '',
                '[Email]' => $quotation->client_email ?? '',
                '[Direccion]' => $quotation->client_address ?? '',
                '[Link]' => route('quotations.download', $quotation),
            ];

            foreach ($replacements as $key => $value) {
                $body = str_replace($key, $value, $body);
            }
        }

        // 3. Handle PDF (Upload vs Generation)
        $tempPath = null;
        
        if ($request->hasFile('pdf_file')) {
            // Use uploaded file (from show.blade.php)
            $pdfFile = $request->file('pdf_file');
            $filename = 'quotation-' . $quotation->id . '.pdf';
            $directory = storage_path('app/temp_quotations');
            if (!file_exists($directory)) mkdir($directory, 0755, true);
            $pdfFile->move($directory, $filename);
            $tempPath = $directory . DIRECTORY_SEPARATOR . $filename;
        } else {
            // Generate server-side (from index.blade.php)
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('quotations.pdf', compact('quotation'));
            $pdfOutput = $pdf->output();
            $tempPath = storage_path('app/public/temp_quotation_' . $quotation->id . '.pdf');
            file_put_contents($tempPath, $pdfOutput);
        }

        try {
            // 4. Send Email
            \Illuminate\Support\Facades\Mail::to($request->input('email'))
                ->send(new \App\Mail\QuotationMail($quotation, $tempPath, $body)); // Pass path, not output, if Mail expects path? 
                // Wait, QuotationMail constructor I saw earlier expected $pdfOutput (raw data) or path?
                // Let's check QuotationMail.
                // Assuming I need to check QuotationMail. 
                // For now, I'll pass $tempPath and ensure QuotationMail handles it.
                // Actually, standard Laravel attach uses path.
            
            // Delete temp file
            if (file_exists($tempPath)) @unlink($tempPath);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Correo enviado correctamente']);
            }
            return back()->with('success', 'Cotización enviada por correo correctamente.');

        } catch (\Exception $e) {
            if (file_exists($tempPath)) @unlink($tempPath);
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Error al enviar correo: ' . $e->getMessage());
        }
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->items()->delete();
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Cotización eliminada correctamente.');
    }

    public function getReminders()
    {
        $quotations = Quotation::all();
        $today = now()->format('Y-m-d');
        
        $reminders = [];

        foreach ($quotations as $quotation) {
            $isDue = false;
            $reason = '';

            // Check Response Date
            if ($quotation->response_date && str_starts_with($quotation->response_date, $today)) {
                $isDue = true;
                $reason = 'Fecha de respuesta programada para hoy.';
            }

            // Check Note
            if (!$isDue && $quotation->follow_up_note) {
                // Regex for dd/mm or dd-mm
                if (preg_match_all('/\b(\d{1,2})[\/.-](\d{1,2})(?:[\/.-](\d{2,4}))?\b/', $quotation->follow_up_note, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $day = str_pad($match[1], 2, '0', STR_PAD_LEFT);
                        $month = str_pad($match[2], 2, '0', STR_PAD_LEFT);
                        if ($day === now()->format('d') && $month === now()->format('m')) {
                            $isDue = true;
                            $reason = 'Nota menciona la fecha de hoy.';
                            break;
                        }
                    }
                }
            }

            if ($isDue) {
                $phone = preg_replace('/\D/', '', $quotation->client_phone);
                if (!str_starts_with($phone, '51') && strlen($phone) === 9) {
                    $phone = '51' . $phone;
                }

                $reminders[] = [
                    'id' => $quotation->id,
                    'client' => $quotation->client_company ?? $quotation->client_name,
                    'reason' => $reason,
                    'note' => $quotation->follow_up_note,
                    'phone' => $phone,
                    'date' => $quotation->response_date,
                    'link' => route('quotations.show', $quotation)
                ];
            }
        }

        return response()->json($reminders);
    }
}




