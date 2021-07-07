<?php

namespace app\components\context\jwt;


use Firebase\JWT\JWT;


/**
 * Class BasicJwtComponent
 */
class BasicJwtComponent implements JwtComponentInterface
{
    /**
     * @var string Jwt secret key
     */
    private string $key;

    /**
     * @var int Expire time for access token in seconds
     */
    private int $accessExpire;

    /**
     * @var int expire time for refresh token in seconds
     */
    private int $refreshExpire;

    private array $algorithm;

    /**
     * BasicJwtComponent constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->key = $config['key'];
        $this->accessExpire = $config['accessExpire'];
        $this->refreshExpire = $config['refreshExpire'];
        $this->algorithm = $config['algorithm'];
    }


    /**
     * @inheritDoc
     */
    public function generateTokensByUserId(int $userId): array
    {
        $accessPayload = [
            'type' => 'access',
            'usr' => $userId,
            'exp' => time() + $this->accessExpire,

        ];
        $refreshPayload = [
            'type' => 'refresh',
            'usr' => $userId,
            'exp' => time() + $this->refreshExpire,
        ];
        $accessToken = JWT::encode($accessPayload, $this->key);
        $refreshToken = JWT::encode($refreshPayload, $this->key);

        return [
            'accessToken' => $accessToken,
            'accessTokenExp' => $accessPayload['exp'],
            'refreshToken' => $refreshToken,
            'refreshTokenExp' => $refreshPayload['exp'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function decodeRefreshToken($refreshToken): object
    {
        return Jwt::decode($refreshToken, $this->key, $this->algorithm);
    }
}
