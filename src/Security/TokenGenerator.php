<?php

namespace App\Security;



class TokenGenerator
{
    public const CHARACTERS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    public const TOKEN_SIZE = 20;
    public static function generate()
    {
        $result = "";
        $length = strlen(TokenGenerator::CHARACTERS);
        for ($index = 0; $index < TokenGenerator::TOKEN_SIZE; $index++) {
            $result .= TokenGenerator::CHARACTERS[random_int(0, $length - 1)];
        }
        return $result;
    }
}
