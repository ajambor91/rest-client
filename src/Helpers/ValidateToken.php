<?php
namespace AJ\Rest\Helpers;
class ValidateToken {
    private const pattern = '/^[a-zA-Z0-9-_]+\.[a-zA-Z0-9-_]+\.[a-zA-Z0-9-_]+$/';

    public static function validateToken($token)
    {
        return preg_match(self::pattern, $token) !== 0;
    }
}