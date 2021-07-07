<?php

use app\components\context\jwt\BasicJwtComponent;
use app\components\context\jwt\JwtComponentInterface;
use app\components\context\user\ActiveRecordUserComponent;
use app\components\context\user\UserComponentInterface;

return [
    JwtComponentInterface::class => function () {
        return new BasicJwtComponent(
            Yii::$app->params['jwt']
        );
    },
    UserComponentInterface::class => function () {
        return new ActiveRecordUserComponent(
            Yii::$app->get(JwtComponentInterface::class)
        );
    }
];
