<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AdoptionRequest extends Model
{
    use HasFactory;


    protected $fillable = [
        'adopter_id',
        'animal_id',
        'message',
        'request_date',
        'adoption_date',
        'status',
        'user_id',
    ];

    public function adopter(): BelongsTo
    {
        return $this->belongsTo(Adopter::class);
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    protected function casts(): array
    {
        return [
            'request_date' => 'datetime',
            'adoption_date' => 'datetime',
        ];
    }
}
