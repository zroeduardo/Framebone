<?php
    namespace Packages\Framebone\Orm\Interfaces;

    /*//////////
        Framebone © 2014. Todos os direitos reservados a seus 
        respectivos proprietários.

        Author: Eduardo Segura
        E-mail: zro.eduardo@gmail.com
    /*/////////

    /*//////////
        Interface para mappear o banco de dados.
    /*/////////

    interface Mapper
    {
        public function find($criteria);
        public function findById($id);
        public function findByCid($cid);
        public function insert($entity);
        public function update($entity, $col);
        public function delete(array $values, $col);
        public function lastId();
    }