<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuitableType extends Model
{
    use HasFactory;
    protected $fillable = ['key', 'name'];

    public function animals(): BelongsToMany
    {
        return $this->belongsToMany(Animal::class, 'animal_suitable_type', 'suitable_type_id', 'animal_id');
    }
}
