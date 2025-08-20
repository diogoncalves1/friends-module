<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FriendshipRepository;

class FriendshipController extends Controller
{
    private FriendshipRepository $friendshipRepository;

    public function __construct(FriendshipRepository $friendshipRepository)
    {
        $this->friendshipRepository = $friendshipRepository;
    }

    public function send(string $id)
    {
        $response = auth()->user()->sendFriendRequest($id);

        return $response;
    }

    public function accept(string $id)
    {
        $response = auth()->user()->acceptFriendRequest($id);

        return $response;
    }

    public function decline(string $id)
    {
        $response = auth()->user()->declineFriendRequest($id);

        return $response;
    }

    public function remove(string $id)
    {
        $response = auth()->user()->removeRelation($id);

        return $response;
    }

    public function block(string $id)
    {
        $response = auth()->user()->blockUser($id);

        return $response;
    }

    public function listFriends()
    {
        $response = $this->friendshipRepository->listFriends('accept');

        return $response;
    }

    public function listPending()
    {
        $response = $this->friendshipRepository->listFriends('pending');

        return $response;
    }
}
