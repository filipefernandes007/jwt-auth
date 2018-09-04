<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace App\Model;


    class UserModel
    {
        /** @var int|null */
        private $id;

        /** @var string */
        private $username;

        /** @var string */
        private $password;

        /**
         * User constructor.
         * @param int|null $id
         * @param string|null   $username
         * @param string|null   $password
         */
        public function __construct(int $id = null, string $username = null, string $password = null)
        {
            $this->id       = $id;
            $this->username = $username;
            $this->password = $password;
        }

        /**
         * @return int
         */
        public function getId() : int
        {
            return $this->id;
        }

        /**
         * @param int $id
         * @return UserModel
         */
        public function setId(int $id) : UserModel
        {
            $this->id = $id;

            return $this;
        }

        /**
         * @return string
         */
        public function getUsername() : string
        {
            return $this->username;
        }

        /**
         * @param string $username
         * @return UserModel
         */
        public function setUsername(string $username) : UserModel
        {
            $this->username = $username;

            return $this;
        }

        /**
         * @return string
         */
        public function getPassword() : string
        {
            return $this->password;
        }

        /**
         * @param string $password
         * @return UserModel
         */
        public function setPassword(string $password) : UserModel
        {
            $this->password = $password;

            return $this;
        }

        /**
         * @return array
         */
        public function toArray() : array {
            return [
                'id'       => $this->id,
                'username' => $this->username
            ];
        }


    }