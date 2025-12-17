<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'specie_id', 'user_id',
        'pictures',
    ];

    protected $casts = [
        'suitable' => 'array',
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

    public function specie(): BelongsTo
    {
        return $this->belongsTo(Specie::class);
    }

    public function race(): HasOneThrough
    {
        return $this->hasOneThrough(
            Race::class,
            Specie::class,
            'id',
            'id',
            'specie_id',
            'race_id'
        );
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
