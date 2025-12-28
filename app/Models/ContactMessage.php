<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'send_date',
        'address',
        'number',
        'cp',
        'city',
    ];
    protected $casts = [
        'send_date' => 'datetime',
    ];

    const TYPE_CONTACT = 'contact';
    const TYPE_VOLUNTEER = 'volunteer';
    const STATUS_NEW = 'nouveau';
    const STATUS_READ = 'lu';
    const STATUS_ARCHIVED = 'archivé';

    public function isContact()
    {
        return $this->type === self::TYPE_CONTACT;
    }

    public function isVolunteer()
    {
        return $this->type === self::TYPE_VOLUNTEER;
    }

    public function scopeContact($query)
    {
        return $query->where('type', self::TYPE_CONTACT);
    }

    public function scopeVolunteer($query)
    {
        return $query->where('type', self::TYPE_VOLUNTEER);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
