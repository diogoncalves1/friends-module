<?php

namespace App\Exceptions;

use Exception;

class FriendRequestAlreadySentException extends Exception
{
    protected $message = 'Já foi feito um pedido de amizade';
    protected $code = 500;
}
