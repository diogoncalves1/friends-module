<?php

namespace App\Exceptions;

use Exception;

class FriendRequestNotFoundException extends Exception
{
    protected $message;
    protected $code = 404;

    public function __construct()
    {
        parent::__construct(__('friends::exceptions.friendships.friendRequestNotFoundException'), $this->code);
    }
}
