<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specie extends Model
{
    use HasFactory;

    protected $fillable=['name', 'race_id'];
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
