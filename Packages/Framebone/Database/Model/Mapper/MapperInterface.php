<?php

    namespace Packages\Framebone\Database\Model\Mapper;

    interface MapperInterface
    {
        public function findById($id);

        public function findByCid($cid);
       
        public function find($criteria);

        public function insert($entity);

        public function update($entity, $col);

        public function delete(array $values, $col);

        public function getInsertId();
    }