<?php

namespace app\components\context\user\exceptions;

/**
 * Class ErrorUserNotFoundByRefreshToken.php
 * Исключение, если пользователь не найден по refresh токену
 */
class ErrorUserNotFoundByRefreshToken extends UserComponentException
{
    /**
     * ErrorUserNotFoundByRefreshToken constructor
     */
    public function __construct()
    {
        parent::__construct("User not found");
    }
}
