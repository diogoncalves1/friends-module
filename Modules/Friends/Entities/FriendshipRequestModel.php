<?php

namespace Modules\Friends\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FriendshipRequestModel extends Model
{
    use HasFactory;

    protected $table = 'friendship_requests';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Friends\Database\Factories\FriendshipRequestFactory::new();
    }
}
