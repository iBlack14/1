<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'client_name',
        'client_company',
        'client_ruc',
        'client_phone',
        'client_email',
        'client_address',
        'date',
        'subtotal',
        'igv',
        'total',
        'response_date',
        'follow_up_message',
        'follow_up_note',
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
