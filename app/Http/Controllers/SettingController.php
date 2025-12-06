<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ServiceImage;
use App\Models\ServiceMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $images = ServiceImage::orderBy('order')->get();
        $services = $this->getAvailableServices();
        $mappings = ServiceMapping::with('serviceImage')->orderBy('service_name')->orderBy('order')->get();
        
        return view('settings.index', compact('settings', 'images', 'services', 'mappings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Ajustes actualizados correctamente.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
            'name' => 'required|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Store in public/service_images (requires storage:link)
            $path = $file->storeAs('service_images', $filename, 'public');

            ServiceImage::create([
                'name' => $request->name,
                'filename' => $filename,
                'path' => $path,
                'is_active' => true,
                'order' => ServiceImage::max('order') + 1,
            ]);

            return back()->with('success', 'Imagen subida correctamente.');
        }

        return back()->with('error', 'No se pudo subir la imagen.');
    }

    public function deleteImage(ServiceImage $image)
    {
        // Check if image is used in mappings
        if ($image->serviceMappings()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la imagen porque está asignada a uno o más servicios. Elimina las asignaciones primero.');
        }

        // Delete file from storage
        Storage::disk('public')->delete($image->path);
        
        // Delete record
        $image->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }

    public function updateMapping(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string',
            'service_image_id' => 'required|exists:service_images,id',
        ]);

        // Create new mapping
        ServiceMapping::create([
            'service_name' => $request->service_name,
            'service_image_id' => $request->service_image_id,
            'order' => ServiceMapping::where('service_name', $request->service_name)->count(),
        ]);

        return back()->with('success', 'Imagen asignada al servicio correctamente.');
    }

    public function deleteMapping(ServiceMapping $mapping)
    {
        $mapping->delete();
        return back()->with('success', 'Asignación eliminada correctamente.');
    }

    private function getAvailableServices()
    {
        return [
            'WEB INFORMATIVA',
            'WEB E-COMMERCE',
            'WEB AULA VIRTUAL',
            'POSICIONAMIENTO SEO',
            'LICENCIA DE ANTIVIRUS',
            'PLUGIN YOAST SEO',
            'RESTRUCTURACION BASICA',
            'RESTRUCTURACION E-COMMERCE',
            'WEB FUSION E-COMMERCE',
            'WEB FUSION AULA VIRTUAL',
            'OTRO'
        ];
    }
}
