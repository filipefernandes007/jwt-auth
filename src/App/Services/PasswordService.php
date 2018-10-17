<?php
    /**
     * Created by PhpStorm.
     * User: Filipe <filipefernandes007@gmail.com>
     * Date: 17/10/2018
     * Time: 21:59
     */

    namespace App\Services;

    class PasswordService
    {
        /**
         * @param string $pwd
         * @return string
         */
        public static function encrypt(string $pwd) : string {
            return password_hash($pwd, PASSWORD_BCRYPT);
        }

        /**
         * @param string $pwd
         * @param string $hash
         * @return bool
         */
        public static function verify(string $pwd, string $hash) : bool {
            return password_verify($pwd, $hash);
        }
    }