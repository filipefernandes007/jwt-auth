<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace App\Repository;


    use App\Base\BaseModel;
    use App\Base\BasePDORepository;
    use App\Model\UserModel;
    use App\Services\PasswordService;

    /**
     * Class UserRepository
     * @package App\Repository
     */
    class UserRepository extends BasePDORepository
    {
        /**
         * @param int $id
         * @return UserModel
         */
        public function find(int $id) : ?BaseModel {
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
         * @param UserModel|BaseModel $user
         * @return bool
         */
        public function save(BaseModel $user) : bool {
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