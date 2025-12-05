<?php

namespace App\Enum;

enum ContactMessageStatus: string
{
    case New = 'new';
    case Read = 'read';
    case Archived = 'archived';
    public function isNew(): bool
    {
        return $this === self::New;
    }

    public function isRead(): bool
    {
        return $this === self::Read;
    }
    public function isArchived(): bool
    {
        return $this === self::Archived;
    }
}
