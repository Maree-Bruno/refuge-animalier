<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specie extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }

    public function races(): HasMany
    {
        return $this->hasMany(Race::class);
    }
    public function vaccines(): HasMany
    {
        return $this->hasMany(Vaccine::class);
    }
}
