<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Friendship extends Model
{
    /** @use HasFactory<\Database\Factories\FriendshipFactory> */
    use HasFactory;

    protected $table = "friendships";
    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
    public function receiver()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSender($query, $senderId)
    {
        return $query->where('sender_id', $senderId);
    }

    public function scopeReceiver($query, $receiverId)
    {
        return $query->where('receiver_id', $receiverId);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
