<?php

namespace App\Exceptions;

use Exception;

class AlreadyFriendsException extends Exception
{
    protected $message = 'Já são amigos';
    protected $code = 500;
}
