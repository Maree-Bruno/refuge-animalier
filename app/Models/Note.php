<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'notable_type',
        'notable_id',
    ];

    protected $appends = ['notable_name'];

    public function notable(): MorphTo
    {
        return $this->morphTo();
    }
    public function getNotableNameAttribute(): ?string
    {
        if (! $this->relationLoaded('notable') || ! $this->notable) {
            return null;
        }

        if ($this->notable instanceof Animal) {
            return $this->notable->name;
        }

        if ($this->notable instanceof AdoptionRequest) {
            $adopter = optional($this->notable->adopter)->name ?? 'Adoptant inconnu';
            $animal  = optional($this->notable->animal)->name ?? 'Animal inconnu';

            return "{$adopter} - {$animal}";
        }

        return 'Inconnu';
    }
}
