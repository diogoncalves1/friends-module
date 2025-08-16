<?php

namespace App\Exceptions;

use Exception;

class FriendRequestNotFoundException extends Exception
{
    protected $message = 'Pedido nao encontrado';
    protected $code = 404;
}
