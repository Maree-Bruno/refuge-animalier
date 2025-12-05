<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Race extends Model
{
    protected $fillable = ['name'];
    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }

    public function specie(): HasOne
    {
        return $this->hasOne(Specie::class);
    }

}
