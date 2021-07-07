<?php

namespace app\components\context\user;


/**
 * Interface UserComponentInterface
 * Интерфейс компонентов для работы с пользователями
 */
interface UserComponentInterface
{
    /**
     * Генерация jwt токенов пользователя по номеру телефона и паролю
     * @param string $phone номер телефона пользователя
     * @param string $password пароль
     * @return array jwt токены
     */
    public function generateTokensByPhoneAndPassword(string $phone, string $password): array;


    /**
     * Генерация jwt токенов пользователя по refresh токену
     * @param string $refreshToken
     * @return array jwt токены
     */
    public function generateTokensByRefreshToken(string $refreshToken): array;

}
