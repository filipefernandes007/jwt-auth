<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace App\Repository;


    use App\Model\UserModel;

    class UserRepository
    {
        /** @var \Slim\Container */
        protected $container;

        /**
         * User constructor.
         * @param \Slim\Container $container
         */
        public function __construct(\Slim\Container $container) {
            $this->container = $container;
        }

        /**
         * @param int $id
         * @return UserModel
         * @throws \Interop\Container\Exception\ContainerException
         */
        public function find(int $id) : UserModel {
            /** @var \PDO $db */
            $db  = $this->container->get('settings')['db'];

            $sql = 'SELECT * FROM user WHERE id = :id';

            /** @var \PDOStatement $stm */
            $stm = $db->prepare($sql);

            $stm->bindParam(':id', $id, \PDO::PARAM_INT);
            $stm->execute();
            $objectFetched = $stm->fetchObject();

            return new UserModel($objectFetched->id, $objectFetched->username, $objectFetched->password);
        }

        /**
         * @param string $username
         * @param string $password
         * @return UserModel|null
         * @throws \Interop\Container\Exception\ContainerException
         */
        public function findByCredentials(string $username, string $password) : ?UserModel {
            /** @var \PDO $db */
            $db  = $this->container->get('settings')['db'];
            $sql = "SELECT * FROM user WHERE username = :username AND password = :password";

            /** @var \PDOStatement $stm */
            $stm = $db->prepare($sql);

            $stm->bindParam(':username', $username, \PDO::PARAM_STR);
            $stm->bindParam(':password', $password, \PDO::PARAM_STR);

            $stm->execute();
            $objectFetched = $stm->fetchObject();

            return new UserModel($objectFetched->id, $objectFetched->username, $objectFetched->password);
        }

    }