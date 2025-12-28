<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'month',
        'year',
        'start_date',
        'end_date',
        'adopted_animals',
        'refuged_animals',
        'accepted_requests',
        'in_progress_requests',
        'animals_by_status',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'animals_by_status' => 'array',
    ];

    public function getLabelAttribute(): string
    {
        return \Carbon\Carbon::create($this->year, $this->month, 1)
            ->locale('fr')
            ->isoFormat('MMMM YYYY');
    }

    public function getTotalAnimalsAttribute(): int
    {
        return $this->adopted_animals + $this->refuged_animals;
    }
}
