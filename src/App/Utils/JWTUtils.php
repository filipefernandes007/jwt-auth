<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace App\Utils;


    use Firebase\JWT\SignatureInvalidException;

    class JWTUtils
    {
        /**
         * @param string $jwt
         * @param string $jwtSecretKey
         * @return object
         */
        public static function decodeJwt(string $jwt, string $jwtSecretKey) {
            return \Firebase\JWT\JWT::decode(trim(str_replace('Bearer', '', $jwt)), $jwtSecretKey, ['HS256']);
        }

        /**
         * @param string $jwt
         * @param string $jwtSecretKey
         * @return bool
         */
        public static function validSignature(string $jwt, string $jwtSecretKey) : bool {
            try {
                if (\is_object(self::decodeJwt($jwt, $jwtSecretKey))) {
                    return true;
                }
            } catch (SignatureInvalidException $e) {
                return false;
            }

            return false;
        }
    }