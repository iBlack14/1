<?php

use Illuminate\Support\Facades\Route;
use App\Models\Quotation;

Route::get('/test-images', function() {
    $quotation = Quotation::with('items')->latest()->first();
    
    if (!$quotation) {
        return 'No quotations found';
    }
    
    $output = "Quotation ID: {$quotation->id}\n\n";
    
    foreach ($quotation->items as $item) {
        $output .= "Service: {$item->service_name}\n";
        $output .= "Image Path: " . ($item->image_path ?? 'NULL') . "\n";
        $output .= "---\n";
    }
    
    return '<pre>' . $output . '</pre>';
});
