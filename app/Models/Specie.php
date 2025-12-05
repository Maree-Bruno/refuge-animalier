<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specie extends Model
{
    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }
}
