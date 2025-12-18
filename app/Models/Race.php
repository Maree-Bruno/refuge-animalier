<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Race extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'specie_id'];

    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }

    public function specie(): BelongsTo
    {
        return $this->belongsTo(Specie::class);
    }
}
