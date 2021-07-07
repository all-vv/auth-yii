<?php
/** @noinspection PhpMissingFieldTypeInspection */
/** @noinspection PhpMissingParamTypeInspection */

/**
 * Parent controller for API
 */

namespace app\controllers;

use Yii;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

/**
 * Class ApiController
 * Базовый класс API контроллеров
 */
abstract class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public bool $requiredApiKey = true;

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => $this->getActionVerbs(),
            ],
            [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                ],

            ]
        ];
    }

    /**
     * Define HTTP verbs for controller actions
     * Example:
     * return [
     *      'logout' => ['post'],
     * ];
     *
     * @return string[][]
     */
    abstract protected function getActionVerbs(): array;

    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @inheritDoc
     * @throws UnauthorizedHttpException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        Yii::$app->response->headers->set('x-server', Yii::$app->params['server']);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $headers = Yii::$app->request->headers;

        /** @var ApiController $controller */
        $controller = $action->controller;
        if ($controller->requiredApiKey && $headers['x-api-key'] != Yii::$app->params['x-api-key']) {
            throw new UnauthorizedHttpException('Invalid API key');
        }
        if (!$headers['x-device-id']) {
            throw new UnauthorizedHttpException('Missing x-device-id header');
        }
        if (!$headers['x-app']) {
            throw new UnauthorizedHttpException('Missing x-app header');
        }
        return parent::beforeAction($action);
    }

    /**
     * @return int
     */
    public function errorAction(): int
    {
        return 404;
    }

}
