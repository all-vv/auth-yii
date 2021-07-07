<?php

namespace app\components\context\user\exceptions;

/**
 * Class ErrorUserNotFoundByPhone
 * Исключение, если пользователь не найден по номеру телефона
 */
class ErrorUserNotFoundByPhone extends UserComponentException
{
    /**
     * ErrorUserNotFoundByPhone constructor.
     *
     * @param string $phone Номер телефона пользователя
     */
    public function __construct(string $phone)
    {
        parent::__construct("User by phone: $phone not found");
    }
}
