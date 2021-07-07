<?php

namespace app\models\user;

use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $phone
 * @property string refresh_token
 */
class User extends ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return 'user';
    }
}
