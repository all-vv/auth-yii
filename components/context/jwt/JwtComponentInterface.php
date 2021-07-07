<?php

namespace app\components\context\jwt;


/**
 * Interface JwtComponentInterface
 * Интерфейс компонентов для работы jwt токенами
 */
interface JwtComponentInterface
{
    /**
     * Генерация jwt токенов для пользователя по идентификатору
     * @param int $userId идентификатор пользователя
     * @return array массив токенов
     */
    public function generateTokensByUserId(int $userId): array;


    /**
     * Декодирование refresh токена
     * @param $refreshToken
     * @return object
     */
    public function decodeRefreshToken($refreshToken): object;

}
