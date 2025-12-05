<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coat extends Model
{
    protected $fillable = ['name'];
    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }
}
