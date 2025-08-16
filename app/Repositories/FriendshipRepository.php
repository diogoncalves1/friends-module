<?php

namespace App\Repositories;

use App\Core\Helpers;
use App\Exceptions\AlreadyFriendsException;
use App\Exceptions\FriendRequestAlreadySentException;
use App\Exceptions\FriendRequestLimitExceededException;
use App\Exceptions\FriendRequestNotFoundException;
use App\Exceptions\SelfFriendshipException;
use App\Exceptions\UserBlockedException;
use App\Models\Friendship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FriendshipRepository
{
    public function send(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {

                if ($this->isSelf($id)) throw new SelfFriendshipException();

                if ($this->areFriends($id)) throw new AlreadyFriendsException();

                if ($this->hasPendingRequest($id)) throw new FriendRequestAlreadySentException();

                if ($this->isBlocked($id)) throw new UserBlockedException();

                if ($this->exceededDeclines($id, 3, 30)) throw new FriendRequestLimitExceededException();

                Friendship::create([
                    'sender_id' => auth()->id(),
                    'receiver_id' => $id,
                    'status' => 'pending'
                ]);

                return response()->json(['success' => true, 'message' => '']);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode())
                return response()->json(['error' => true, 'message' => $e->getMessage()], $e->getCode());
            return response()->json(['error' => true, 'message' => ''], 500);
        }
    }

    public function accept(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {

                if ($this->isSelf($id)) throw new SelfFriendshipException();

                if ($this->areFriends($id)) throw new AlreadyFriendsException();

                if (!$this->hasPendingRequest($id)) throw new FriendRequestNotFoundException();

                if ($this->isBlocked($id)) throw new UserBlockedException();

                Friendship::sender($id)
                    ->receiver(auth()->id())
                    ->update(['status' => 'accepted']);

                return response()->json(['success' => true, 'message' => '']);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode())
                return response()->json(['error' => true, 'message' => $e->getMessage()], $e->getCode());
            return response()->json(['error' => true, 'message' => ''], 500);
        }
    }

    public function decline(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {

                Friendship::sender($id)->receiver(auth()->id())->update(['status' => 'declined']);

                return response()->json(['success' => true, 'message' => '']);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode())
                return response()->json(['error' => true, 'message' => $e->getMessage()], $e->getCode());
            return response()->json(['error' => true, 'message' => ''], 500);
        }
    }

    public function listFriends(string $status) {}

    public function remove(string $id) {}

    public function block(string $id) {}


    public function isSelf(string $receiverId)
    {
        return $receiverId == auth()->id();
    }
    public function areFriends(string $receiverId)
    {
        return Friendship::sender($receiverId)->receiver(auth()->id())->status('accepted')->exists() || Friendship::sender(auth()->id())->receiver($receiverId)->status('accepted')->exists();
    }
    public function hasPendingRequest(string $receiverId)
    {
        return Friendship::sender($receiverId)->receiver(auth()->id())->status('pending')->exists() || Friendship::sender(auth()->id())->receiver($receiverId)->status('pending')->exists();
    }
    public function isBlocked(string $receiverId)
    {
        return Friendship::sender(auth()->id())->receiver($receiverId)->status('blocked')->exists() || Friendship::sender($receiverId)->receiver(auth()->id())->status('blocked')->exists();
    }
    public function exceededDeclines(string $receiverId, int $maxDeclines, int $days)
    {
        $limitDate = Helpers::getOldDate($days);

        return Friendship::sender(auth()->id())->receiver($receiverId)->status('decline')->where('created_at', '>=', $limitDate)->count() >= $maxDeclines;
    }
}
