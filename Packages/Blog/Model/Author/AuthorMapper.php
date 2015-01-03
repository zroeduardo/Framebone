<?php

    namespace Packages\Blog\Model\Author;

    use \Packages\Framebone\Database\Model\Mapper as Mapper;

    class AuthorMapper extends Mapper\MapperAbstract
    {
        protected $_entityTable = 'authors';
        protected $_entityClass = '\Packages\Blog\Model\Author\Author';

        /**
         * Create an author entity with the supplied data
         */
        protected function _createEntity(array $data)
        {
            $author = new $this->_entityClass(array(
                'id'    => $data['id'],
                'name'  => $data['name'],
                'email' => $data['email']
            ));
            return $author;
        }
    }