<?php

namespace App\Exceptions;

use Exception;

class SelfFriendshipException extends Exception
{
    protected $message = 'Impossivel enviar pedido para si mesmo';
    protected $code = 500;
}
