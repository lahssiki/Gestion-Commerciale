<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
     protected $fillable = [
        'client_id', 'user_id', 'invoice_number', 
        'date', 'total_ht', 'tva', 'total_ttc', 'status'
    ];

    protected $casts = [
        'date' => 'date',
        'total_ht' => 'decimal:2',
        'tva' => 'decimal:2',
        'total_ttc' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function calculateTotals(): void
    {
        $this->total_ht = $this->items->sum('total');
        $this->tva = $this->total_ht * 0.20; // 20% TVA
        $this->total_ttc = $this->total_ht + $this->tva;
        $this->save();
    }
}
