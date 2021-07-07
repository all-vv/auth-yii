<?php

namespace app\controllers;

use app\components\context\user\exceptions\ErrorInvalidPassword;
use app\components\context\user\exceptions\ErrorUserNotFoundByPhone;
use app\components\context\user\exceptions\ErrorUserNotFoundByRefreshToken;
use app\components\context\user\UserComponentInterface;
use app\components\helpers\Misc;
use app\components\helpers\Validator;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends ApiController
{
    /* @var UserComponentInterface */
    private $userComponent;

    /**
     * @inheritDoc
     */
    protected function getActionVerbs(): array
    {
        return [
            'auth' => ['post'],
            'refresh' => ['post'],
        ];
    }


    /**
     * @throws InvalidConfigException
     */
    public function __construct(string $id, $module, $config = [])
    {
        $this->userComponent = Yii::$app->get(UserComponentInterface::class);
        parent::__construct($id, $module, $config);
    }

    /**
     * Action генерации jwt токенов для пользователя по номеру телефона и паролю
     * @throws InvalidConfigException
     * @throws BadRequestHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionAuth()
    {
        $postData = Yii::$app->request->post();
        $errors = Validator::validate(
            $postData,
            [
                [['phone', 'password'], 'required'],
            ]
        );
        if (count($errors)) {
            throw new BadRequestHttpException(json_encode($errors));
        }
        try {
            return $this->userComponent->generateTokensByPhoneAndPassword(
                Misc::preparePhone($postData['phone']),
                Misc::prepareString($postData['password']));
        } catch (ErrorUserNotFoundByPhone | ErrorInvalidPassword $exception) {
            throw new UnauthorizedHttpException($exception->getMessage());
        }
    }

    /**
     * Action генерации jwt токенов для пользователя по refresh токену
     * @throws InvalidConfigException
     * @throws BadRequestHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionRefresh()
    {
        $postData = Yii::$app->request->post();
        $errors = Validator::validate(
            $postData,
            [
                [['refreshToken'], 'required'],
            ]
        );
        if (count($errors)) {
            throw new BadRequestHttpException(json_encode($errors));
        }
        try {
            return $this->userComponent->generateTokensByRefreshToken(
                Misc::prepareString($postData['refreshToken'])
            );
        } catch (ExpiredException | ErrorUserNotFoundByRefreshToken | SignatureInvalidException $exception) {
            throw new UnauthorizedHttpException($exception->getMessage());
        }
    }


}