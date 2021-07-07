<?php

namespace app\components\helpers;

use Closure;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;

/**
 * Class Validator
 * Валидатор для проверки входных данных
 */
class Validator
{
    /**
     * Метод валидации входных данных
     *
     * @param array $values
     * @param array $rules Массив параметров для проверки
     * @return array|null
     * @throws InvalidConfigException
     */
    public static function validate(array $values, array $rules): ?array
    {
        $values = static::fillEmptyFieldsByRules($values, $rules);
        $model = DynamicModel::validateData($values, $rules);
        return $model->getErrors();
    }

    /**
     * Возвращает валидатор, проверяющий, является ли значение массивом.
     *
     * @return Closure
     */
    public static function getIsArrayValidator(): Closure
    {
        return function ($field) {
            /** @var DynamicModel $model */
            $model = $this;
            if (!is_array($model->$field)) {
                $model->addError($field, 'must be an array');
            }
        };
    }

    /**
     * Забивает `null` в те ключи массива, которые есть в `$rules`, но отсутствуют в `$values`.
     * Этот хак нужен для того, чтобы валидатор фреймворка не выкидывал исключение, что поля нет.
     *
     * @param array $values
     * @param array $rules
     * @return array
     */
    private static function fillEmptyFieldsByRules(array $values, array $rules): array
    {
        foreach ($rules as $rule) {
            $fields = is_array($rule) ? reset($rule) : null;
            if (is_string($fields)) {
                $fields = [$fields];
            }
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $values[$field] ??= null;
                }
            }
        }
        return $values;
    }
}
