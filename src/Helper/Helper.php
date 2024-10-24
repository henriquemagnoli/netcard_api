<?php

namespace Netcard\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

class Helper
{
    public static function getJWTData(string $accessToken) : stdClass
    {
        try
        {
            $decoded = JWT::decode($accessToken, new Key($_ENV['SECRET_KEY'], 'HS256'));

            return $decoded;
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
}

?>