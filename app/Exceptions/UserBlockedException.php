<?php

namespace App\Exceptions;

use Exception;

class UserBlockedException extends Exception
{
    protected $message = 'Utilizador Bloqueado';
    protected $code = 500;
}
