<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    public function getPriceAttribute($value): string {
        return $value / 100 . "€";
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
