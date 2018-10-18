<?php
    /**
     * Created by PhpStorm.
     * User: Filipe <filipefernandes007@gmail.com>
     * Date: 18/10/2018
     * Time: 11:12
     */

    namespace App\Base;

    /**
     * Class BasePDORepository
     * @package App\Base
     */
    class BasePDORepository
    {
        /** @var \PDO */
        protected $pdo;

        /**
         * UserRepository constructor.
         * @param \PDO $pdo
         */
        public function __construct(\PDO $pdo) {
            $this->pdo = $pdo;
        }

        /**
         * @param BaseModel $model
         * @return bool
         */
        public function save(BaseModel $model) : bool {
            return true;
        }

        /**
         * @param int $id
         * @return BaseModel|null
         */
        public function find(int $id) : ?BaseModel {
            return null;
        }
    }