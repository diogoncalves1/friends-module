<?php

namespace App\Traits;

use App\Repositories\FriendshipRepository;

trait Friendable
{

    public function acceptFriendRequest(string $id)
    {
        return app(FriendshipRepository::class)->accept($id);
    }

    public function sendFriendRequest(string $id)
    {
        return app(FriendshipRepository::class)->send($id);
    }

    public function declineFriendRequest(string $id)
    {
        return app(FriendshipRepository::class)->decline($id);
    }

    public function blockUser(string $id)
    {
        return app(FriendshipRepository::class)->block($id);
    }
}
