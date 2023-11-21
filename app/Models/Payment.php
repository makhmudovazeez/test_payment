<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_PENDING,
        self::STATUS_COMPLETED,
        self::STATUS_EXPIRED,
        self::STATUS_REJECTED,
    ];
    public const STATUS_NEW = 'new';
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_REJECTED = 'rejected';
}
