<?php

namespace app\components\helpers;


class Misc
{

    /**
     * Метод очистки строки номера телефона
     * @param string $phone
     * @return string
     */
    public static function preparePhone(string $phone): string
    {
        return preg_replace("/[^0-9]/", '', $phone);
    }


    /**
     * Метод очистки строки от спецсимволов
     * @param string $string
     * @return string
     */
    public static function prepareString(string $string): string
    {
        return strip_tags(trim($string));
    }
}

