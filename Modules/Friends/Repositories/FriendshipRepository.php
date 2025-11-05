<?php

namespace App\Repositories;

use App\Core\Helpers;
use App\Exceptions\AlreadyFriendsException;
use App\Exceptions\FriendRequestAlreadySentException;
use App\Exceptions\FriendRequestLimitExceededException;
use App\Exceptions\FriendRequestNotFoundException;
use App\Exceptions\FriendshipNotFoundException;
use App\Exceptions\SelfBlockNotAllowedException;
use App\Exceptions\SelfFriendshipException;
use App\Exceptions\UserBlockedException;
use App\Exceptions\UserNotBlockedException;
use App\Models\Friendship;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FriendshipRepository
{
    public function send(Request $request, string $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $user = $request->user();

                if ($this->isSelf($id, 2)) throw new SelfFriendshipException();
                if ($this->areFriends($id, 2)) throw new AlreadyFriendsException();
                if ($this->hasPendingRequest($id, 2)) throw new FriendRequestAlreadySentException();
                if ($this->isBlocked($id, 2)) throw new UserBlockedException();
                if ($this->exceededDeclines($id, 2/*$user->id */, 3, 30,)) throw new FriendRequestLimitExceededException();

                Friendship::create([
                    'sender_id' => 2,
                    'receiver_id' => $id,
                    'status' => 'pending'
                ]);

                return response()->json(['success' => true, 'message' => __('alerts.friendRequestSended')]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode())
                return response()->json(['error' => true, 'message' => $e->getMessage()], $e->getCode());
            return response()->json(['error' => true, 'message' => __('alerts.errorSendFriendRequest')], 500);
        }
    }

    public function accept(Request $request, string $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $user = $request->user();

                if ($this->isSelf($id, 2)) throw new SelfFriendshipException();
                if ($this->areFriends($id, 2)) throw new AlreadyFriendsException();
                if (!$this->hasPendingRequest($id, 2)) throw new FriendRequestNotFoundException();
                if ($this->isBlocked($id, 2)) throw new UserBlockedException();

                Friendship::query()->sender($id)
                    ->receiver(2)
                    ->update(['status' => 'accepted']);

                return response()->json(['success' => true, 'message' => __('alerts.friendRequestAccepted')]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode())
                return response()->json(['error' => true, 'message' => $e->getMessage()], $e->getCode());
            return response()->json(['error' => true, 'message' => __('alerts.errorAcceptFriendRequest')], 500);
        }
    }

    public function decline(Request $request, string $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $user = $request->user();

                if ($this->isSelf($id, 2)) throw new SelfFriendshipException();
                if (!$this->hasPendingRequest($id, 2)) throw new FriendRequestNotFoundException();
                if ($this->isBlocked($id, 2)) throw new UserBlockedException();

                Friendship::query()->sender($id)->receiver(2)->update(['status' => 'declined']);

                return response()->json(['success' => true, 'message' => __('alerts.friendRequestDeclined')]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode())
                return response()->json(['error' => true, 'message' => $e->getMessage()], $e->getCode());
            return response()->json(['error' => true, 'message' => __('alerts.errorDeclineFriendRequest')], 500);
        }
    }

    public function remove(Request $request, string $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $user = $request->user();

                if (!$this->hasRelation($id, 2)) throw new FriendshipNotFoundException();
                if ($this->isBlocked($id, 2)) throw new UserBlockedException();

                Friendship::where(function ($query) use ($id) {
                    $query->receiver($id)->orWhere(2);
                })->orWhere(function ($query) use ($id) {
                    $query->sender($id)->orWhere('receiver_id', 2);
                })->destroy();

                return response()->json(['success' => true, 'message' => __('alerts.friendRemoved')]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode())
                return response()->json(['error' => true, 'message' => $e->getMessage()], $e->getCode());
            return response()->json(['error' => true, 'message' => ''], 500);
        }
    }

    public function block(Request $request, string $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $user = $request->user();

                if ($this->isSelf($id, 3)) throw new SelfBlockNotAllowedException();
                if ($this->isBlocked($id, 3)) throw new UserBlockedException();

                if ($this->hasRelation($id, 3))
                    Friendship::where(function ($query) use ($id) {
                        $query->sender($id)->orWhere("receiver_id", 3);
                    })->where(function ($query) use ($id) {
                        $query->receiver($id)->orWhere('sender_id', 3);
                    })->destroy();

                $dataBlock = [
                    'sender_id' => 3,
                    'receiver_id' => $id,
                    'status' => 'blocked'
                ];

                Friendship::create($dataBlock);

                Log::info("User " . 3 . " blocked user " . $id);
                return response()->json(['success' => true, 'message' => __('alerts.userBlocked')]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode())
                return response()->json(['error' => true, 'message' => $e->getMessage()], $e->getCode());
            return response()->json(['error' => true, 'message' => 'alerts.errorBlockUser'], 500);
        }
    }

    public function unlock(Request $request, string $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $user = $request->user();

                if ($this->isSelf($id, 3)) throw new SelfBlockNotAllowedException();
                if (!$this->hasRelation($id, 3)) throw new FriendshipNotFoundException();
                if (!$this->isBlocked($id, 3)) throw new UserNotBlockedException();

                Friendship::status("blocked")->sender(3)->receiver($id)->delete();

                Log::info("User " . 3 . " unlocked user " . $id);
                return response()->json(['success' => true, 'message' => __('alerts.userUnlocked')]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getCode())
                return response()->json(['error' => true, 'message' => $e->getMessage()], $e->getCode());
            return response()->json(['error' => true, 'message' => 'alerts.errorUnlockUser'], 500);
        }
    }

    public function listFriends(Request $request, string $status)
    {
        $user = $request->user();

        $query = Friendship::with('receiver')->with('sender');

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas("receiver", function ($query) use ($search) {
                    $query->where('name', $search);
                })
                    ->whereHas("sender", function ($query) use ($search) {
                        $query->where('name', $search);
                    });
            });
        }

        $orderColumnIndex = $request->input('order.0.column');
        $orderColumn = $request->input("columns.$orderColumnIndex.data");
        $orderDir = $request->input('order.0.dir');
        if ($orderColumn && $orderDir) {
            $query->orderBy($orderColumn, $orderDir);
        }

        $total = $query->count();

        $friendships = $query->offset($request->start)
            ->limit($request->length)
            ->status($status)
            ->where(function ($query) {
                $query->sender(3)
                    ->orWhere("receiver_id", 3);
            })
            ->get();

        foreach ($friendships as &$friendship) {
            $friendshipId = $friendship->sender_id == 2 ? $friendship->receiver_id : $friendship->sender_id;
            $friendship->friend = $friendship->sender_id == 2 ? $friendship->receiver : $friendship->sender;
            $friendship->actions = "<div class='btn-group'>
                            <button type='button' onclick='modalRemove({$friendshipId})' class='btn btn-default'>
                                <i class='fas fa-user-minus'></i>
                            </button>
                            <button type='button' onclick='modalBlock({$friendshipId})' class='btn btn-default'>
                                <i class='fas fa-ban'></i>
                            </button>
                        </div>";
        }

        $data = [
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $friendships
        ];

        return $data;
    }


    // Private Methods

    public function isSelf(string $receiverId, string $userId)
    {
        return $receiverId == $userId;
    }
    public function areFriends(string $receiverId, string $userId)
    {
        return Friendship::query()->sender($receiverId)->receiver($userId)->status('accepted')->exists() || Friendship::query()->sender($userId)->receiver($receiverId)->status('accepted')->exists();
    }
    public function hasPendingRequest(string $receiverId, string $userId)
    {
        return Friendship::query()->sender($receiverId)->receiver($userId)->status('pending')->exists() || Friendship::query()->sender($userId)->receiver($receiverId)->status('pending')->exists();
    }
    public function isBlocked(string $receiverId, string $userId)
    {
        return Friendship::query()->sender($userId)->receiver($receiverId)->status('blocked')->exists() || Friendship::query()->sender($receiverId)->receiver($userId)->status('blocked')->exists();
    }
    public function exceededDeclines(string $receiverId, string $userId, int $maxDeclines, int $days)
    {
        $limitDate = Helpers::getOldDate($days);

        return Friendship::query()->sender($userId)->receiver($receiverId)->status('decline')->where('created_at', '>=', $limitDate)->count() >= $maxDeclines;
    }
    public function hasRelation(string $userSenderId, string $userId)
    {
        return Friendship::query()->sender($userSenderId)->receiver($userId)->exists() || Friendship::query()->sender($userId)->receiver($userSenderId)->exists();
    }
}
