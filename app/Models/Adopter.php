<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adopter extends Model
{
    use HasFactory;

    protected $fillable=['number','city','cp', 'name', 'email', 'phone', 'address'];
    public function adoptionRequest(): BelongsTo
    {
        return $this->belongsTo(AdoptionRequest::class);
    }

}
