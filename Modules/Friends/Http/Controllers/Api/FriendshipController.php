<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FriendshipRepository;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    private FriendshipRepository $friendshipRepository;

    public function __construct(FriendshipRepository $friendshipRepository)
    {
        $this->friendshipRepository = $friendshipRepository;
    }

    public function send(Request $request, string $id)
    {
        $response = $this->friendshipRepository->send($request, $id);

        return $response;
    }

    public function accept(Request $request, string $id)
    {

        $response = $this->friendshipRepository->accept($request, $id);

        return $response;
    }

    public function decline(Request $request, string $id)
    {
        $response = $this->friendshipRepository->decline($request, $id);

        return $response;
    }

    public function remove(Request $request, string $id)
    {
        $response = $this->friendshipRepository->remove($request, $id);

        return $response;
    }

    public function block(Request $request, string $id)
    {
        $response = $this->friendshipRepository->block($request, $id);

        return $response;
    }

    public function unlock(Request $request, string $id)
    {
        $response = $this->friendshipRepository->unlock($request, $id);

        return $response;
    }

    public function listFriends(Request $request)
    {
        $response = $this->friendshipRepository->listFriends($request, 'accepted');

        return $response;
    }

    public function listPending(Request $request)
    {
        $response = $this->friendshipRepository->listFriends($request, 'pending');

        return $response;
    }

    public function listBlocked(Request $request)
    {
        $response = $this->friendshipRepository->listFriends($request, 'blocked');

        return $response;
    }
}
