<?php
/**
 * Created by PhpStorm.
 * User: Filipe <filipefernandes007@gmail.com>
 * Date: 06/09/2018
 * Time: 15:45
 */

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

/**
 * Class JWTService
 * @package App\Services\JWTService
 */
class JWTService
{
    /**
     * Change this to your own sub identity provider
     *
     * @string
     */
    private const SUB_IDENTITY_PROVIDER = 'filipefernandes007-jwt';

    /**
     * @param string $identity
     * @param array  $data
     * @return null|string
     */
    public function generate(string $identity, array $data = []) : ?string
    {
        $time = time();

        $playLoad = [
            'sub'   => self::SUB_IDENTITY_PROVIDER . '|' . $identity,
            'iss'   => '/auth',
            'iat'   => $time,
            'exp'   => $time + (int) getenv('JWT_EXPIRATION_TIME'),
            'data'  => $data
        ];

        return JWT::encode($playLoad, (string) getenv('JWT_SECRET'), (string) getenv('JWT_ALGORITHM'));
    }

    /**
     * @param object $jwtDecoded
     * @return string|null
     */
    public function getSubId($jwtDecoded) : ?string
    {
        if ($jwtDecoded !== null && property_exists($jwtDecoded, 'sub')) {
            return str_replace(self::SUB_IDENTITY_PROVIDER . '|', '', $jwtDecoded->sub);
        }

        return null;
    }

    /**
     * @param string $jwt
     * @param string $jwtSecretKey
     * @return object
     */
    public static function decode(string $jwt, string $jwtSecretKey) {
        return JWT::decode(trim(str_replace('Bearer', '', $jwt)), $jwtSecretKey, [getenv('JWT_ALGORITHM')]);
    }

    /**
     * @param string $jwt
     * @param string $jwtSecretKey
     * @return bool
     */
    public static function isSignatureValid(string $jwt, string $jwtSecretKey) : bool {
        try {
            if (\is_object(self::decode($jwt, $jwtSecretKey))) {
                return true;
            }
        } catch (SignatureInvalidException $e) {
            return false;
        }

        return false;
    }
}
