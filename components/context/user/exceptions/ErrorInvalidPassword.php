<?php

namespace app\components\context\user\exceptions;


/**
 * Class ErrorInvalidPassword
 * Исключение, если пароль пользователя не проходит валидацию
 */
class ErrorInvalidPassword extends UserComponentException
{
    /**
     * ErrorInvalidPassword constructor.
     */
    public function __construct()
    {
        parent::__construct("Invalid phone or password");
    }
}
