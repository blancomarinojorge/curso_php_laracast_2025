<?php

namespace Core;
class Validator
{
    public static function checkString(string $text, int $minLenght = 1, float $maxLenght = INF)
    {
        $text = trim($text);

        return strlen($text) >= $minLenght && strlen($text) <= $maxLenght;
    }

    public static function email(string $text)
    {
        return filter_var($text, FILTER_VALIDATE_EMAIL);
    }
}