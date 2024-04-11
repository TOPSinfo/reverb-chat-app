<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getAllMessages($receiverId, $senderId): Collection
    {
        return self::query()
            ->whereAny([
                'receiver_id',
                'sender_id',
            ], '=', $receiverId)
            ->whereAny([
                'receiver_id',
                'sender_id',
            ], '=', $senderId)
            ->get();
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
