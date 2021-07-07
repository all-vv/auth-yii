<?php
/**
 * Formatting valid json response
 */

namespace app\components\api;

use Yii;


class Response
{
    /**
     * Return success 200 response array
     *
     * @param null $data
     * @param int $code
     * @return array|null
     */
    public static function sendSuccess($data = null, int $code = 200): ?array
    {
        Yii::$app->response->statusCode = $code;
        return $data;

    }

    /**
     * Return error array with http code
     *
     * @param string $message
     * @param int $code
     * @return array
     */
    public static function sendError(string $message, int $code = 500): array
    {
        $code = $code ?? 500;
        Yii::$app->response->statusCode = $code;
        return ['code' => $code, 'status' => 'fail', 'body' => $message];
    }
}