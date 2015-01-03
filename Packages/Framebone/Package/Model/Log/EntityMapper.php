<?php

    namespace Packages\Framebone\Package\Model\Log;

    use \Packages\Framebone\Database\Model\Mapper as Mapper;

    class EntityMapper extends Mapper\MapperAbstract
    {
        protected $_entityTable = 'fb_pack_log';
        protected $_entityClass = '\Packages\Framebone\Package\Model\Log\Entity';

        /**
         * Create an author entity with the supplied data
         */
        protected function _createEntity(array $data)
        {
            $log = new $this->_entityClass(array(
                'id' => $data['id'],
                'id_update' => $data['id_update'],
                'description' => $data['description'],
                'author' => $data['author'],
                'email' => $data['email'],
            ));
            return $log;
        }
    }