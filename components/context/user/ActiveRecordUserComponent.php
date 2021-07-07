<?php

namespace app\components\context\user;


use app\components\context\jwt\JwtComponentInterface;
use app\components\context\user\exceptions\ErrorInvalidPassword;
use app\components\context\user\exceptions\ErrorUserNotFoundByPhone;
use app\components\context\user\exceptions\ErrorUserNotFoundByRefreshToken;
use app\models\user\User;
use Yii;

/**
 * Class ActiveRecordUserComponent
 */
class ActiveRecordUserComponent implements UserComponentInterface
{
    /**
     * @var JwtComponentInterface
     */
    private JwtComponentInterface $jwtComponent;


    /**
     * ActiveRecordUserComponent constructor.
     * @param JwtComponentInterface $jwtComponent
     */
    public function __construct(JwtComponentInterface $jwtComponent)
    {
        $this->jwtComponent = $jwtComponent;
    }

    /**
     * @inheritDoc
     */
    public function generateTokensByPhoneAndPassword(string $phone, string $password): array
    {
        $user = User::findOne(['phone' => $phone, 'status' => User::STATUS_ACTIVE]);
        if (!$user) {
            throw new ErrorUserNotFoundByPhone($phone);
        }
        if (!Yii::$app->security->validatePassword($password, $user->password_hash)) {
            throw new ErrorInvalidPassword();
        }
        return self::generateAndSaveJwtToken($user);
    }

    /**
     * @inheritDoc
     */
    public function generateTokensByRefreshToken(string $refreshToken): array
    {
        $decode = $this->jwtComponent->decodeRefreshToken($refreshToken);
        $user = User::findOne(['id' => $decode->usr, 'refresh_token' => $refreshToken, 'status' => User::STATUS_ACTIVE]);
        if (!$user) {
            throw new ErrorUserNotFoundByRefreshToken();
        }
        return self::generateAndSaveJwtToken($user);
    }

    /**
     * Генерация jwt токенов и сохранение refresh токена для пользователя
     * @param User $user
     * @return array
     */
    public function generateAndSaveJwtToken(User $user): array
    {
        $tokens = $this->jwtComponent->generateTokensByUserId($user->id);
        $user->updateAttributes(['refresh_token' => $tokens['refreshToken']]);
        return $tokens;
    }
}
