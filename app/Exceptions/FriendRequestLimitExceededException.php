<?php

namespace App\Exceptions;

use Exception;

class FriendRequestLimitExceededException extends Exception
{
    protected $message = 'Limite de pedidos enviados';
    protected $code = 500;
}
