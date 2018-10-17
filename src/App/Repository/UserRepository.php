<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace App\Repository;


    use App\Model\UserModel;
    use App\Services\PasswordService;

    class UserRepository
    {
        /** @var \PDO */
        protected $pdo;

        /**
         * UserRepository constructor.
         * @param \Slim\Container $container
         * @throws \Interop\Container\Exception\ContainerException
         */
        public function __construct(\Slim\Container $container) {
            $this->pdo = $container->get('settings')['db'];
        }

        /**
         * @param int $id
         * @return UserModel
         * @throws \Interop\Container\Exception\ContainerException
         */
        public function find(int $id) : UserModel {
            $sql = 'SELECT * FROM user WHERE id = :id';

            /** @var \PDOStatement $stm */
            $stm = $this->pdo->prepare($sql);

            $stm->bindParam(':id', $id, \PDO::PARAM_INT);
            $stm->execute();
            $objectFetched = $stm->fetchObject();

            return new UserModel($objectFetched->id, $objectFetched->username, $objectFetched->password);
        }

        /**
         * @param string $username
         * @return UserModel
         * @throws \Interop\Container\Exception\ContainerException
         */
        public function findByUsername(string $username) : UserModel {
            $sql = 'SELECT * FROM user WHERE username = :username LIMIT 1';

            /** @var \PDOStatement $stm */
            $stm = $this->pdo->prepare($sql);

            $stm->bindParam(':username', $username, \PDO::PARAM_STR);
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
            /** @var UserModel $user */
            $user = $this->findByUsername($username);

            if ($user !== null && !PasswordService::verify($password, $user->getPassword())) {
                return null;
            }

            return $user;
        }

        /**
         * @param UserModel $user
         * @return bool
         * @throws \Interop\Container\Exception\ContainerException
         */
        public function save(UserModel $user) : bool {
            $sql = "UPDATE user SET password = :password WHERE id = :id";

            try {
                /** @var \PDOStatement $stm */
                $stm = $this->pdo->prepare($sql);

                $stm->bindValue(':id', $user->getId(), \PDO::PARAM_INT);
                $stm->bindValue(':password', $user->getPassword(), \PDO::PARAM_STR);

                $result = $stm->execute();
            } catch (\PDOException $e) {
                throw $e;
            }

            return $result;
        }

    }