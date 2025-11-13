<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogItem extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'url',
        'description',
        'ingredients',
        'price',
    ];

    public function casts(): array
    {
        return [
            'price' => 'integer',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
