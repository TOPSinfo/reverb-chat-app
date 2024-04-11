<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function getUsersWithLatestMessage(): Collection
    {
        $users = self::select('id', 'name')
            ->with('lastMessageSent', 'lastMessageReceived')
            ->get();

        $users = $users->map(function ($user) {
            $user->latestMessage = $user->getLatestMessage($user->lastMessageSent, $user->lastMessageReceived);

            return $user;
        });

        return $users;
    }

    public function getLatestMessage(?Message $latestSentMessage, ?Message $latestReceivedMessage): ?\Illuminate\Database\Eloquent\Model
    {
        if (! $latestSentMessage && ! $latestReceivedMessage) {
            return null;
        }

        if (! $latestSentMessage) {
            return $latestReceivedMessage;
        }

        if (! $latestReceivedMessage) {
            return $latestSentMessage;
        }

        return $latestSentMessage->created_at->gt($latestReceivedMessage->created_at)
            ? $latestSentMessage
            : $latestReceivedMessage;
    }

    public function lastMessageSent(): HasOne
    {
        return $this->hasOne(Message::class, 'sender_id')->latestOfMany();
    }

    public function lastMessageReceived(): HasOne
    {
        return $this->hasOne(Message::class, 'receiver_id')->latestOfMany();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
