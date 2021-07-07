<?php
/**
 * Class for error handling
 */

namespace app\components\api;

use Error;
use Exception;
use Yii;
use yii\web\HttpException;
use yii\web\Response;

class ErrorHandler extends \yii\web\ErrorHandler
{
    /**
     * Overwrite render error
     * @param Error|Exception $exception
     */
    protected function renderException($exception)
    {
        $headers = Yii::$app->response;
        $headers->format = Response::FORMAT_JSON;

        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
        } else {
            $response = new Response();
        }

        $code = ($exception instanceof HttpException)
            ? ($exception->statusCode ?? 500)
            : 500;

        $response->setStatusCode($code);
        $response->data = $this->convertExceptionToArray($exception, $code);
        $response->send();
    }


    /**
     * @param Error|Exception $exception
     * @param null $code
     * @return array
     */
    protected function convertExceptionToArray($exception, $code = null): array
    {
        return \app\components\api\Response::sendError($exception->getMessage(), $code);
    }
}
