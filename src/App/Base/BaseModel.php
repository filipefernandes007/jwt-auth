<?php
    /**
     * Created by PhpStorm.
     * User: Filipe <filipefernandes007@gmail.com>
     * Date: 18/10/2018
     * Time: 11:13
     */

    namespace App\Base;

    /**
     * Class BaseModel
     * @package App\Base
     */
    abstract class BaseModel
    {
        /** @var int|null */
        protected $id;

        /**
         * @return int|null
         */
        public function getId() : ?int
        {
            return $this->id;
        }

        /**
         * @param int $id
         * @return BaseModel
         */
        public function setId(int $id) : self
        {
            $this->id = $id;

            return $this;
        }

    }