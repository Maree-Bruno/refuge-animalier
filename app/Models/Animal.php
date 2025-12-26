<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Animal extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'sex',
        'age',
        'chip',
        'description',
        'outside',
        'published',
        'status',
        'suitable',
        'admission_date',
        'coat_id',
        'note_id',
        'race_id',
        'user_id',
        'pictures',
    ];

    protected $casts = [
        'pictures' => 'array',
        'outside' => 'boolean',
        'published' => 'boolean',
    ];

    public function coat(): BelongsTo
    {
        return $this->belongsTo(Coat::class);
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function specie(): HasOneThrough
    {
        return $this->hasOneThrough(
            Specie::class,
            Race::class,
            'id',
            'id',
            'race_id',
            'specie_id'
        );
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vaccines(): BelongsToMany
    {
        return $this->belongsToMany(Vaccine::class, 'animal_vaccine', 'animal_id', 'vaccine_id');
    }

    public function suitableTypes(): BelongsToMany
    {
        return $this->belongsToMany(SuitableType::class, 'animal_suitable_type', 'animal_id', 'suitable_type_id');
    }
}
