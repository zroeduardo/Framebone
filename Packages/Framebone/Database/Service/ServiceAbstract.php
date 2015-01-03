<?php

    namespace Packages\Framebone\Database\Service;

    use \Packages\Framebone\Database\Model\Mapper as Mapper,
        \Packages\Framebone\Database\Model as Model;

    abstract class ServiceAbstract
    {
        protected $_mapper;

        /**
         * Constructor
         */
        public function __construct(Mapper\MapperAbstract $mapper)
        {
            $this->_mapper = $mapper;
        }

        /**
         * Find an entity by its ID
         */
        public function findById($id)
        {
            return $this->_mapper->findById($id);
        }


        /**
         * Find an entity by its CiD
         */
        public function findByCid($cid)
        {
            return $this->_mapper->findByCid($cid);
        }

        /**
         * Find the entities that meet the specified conditions
         * (find all entities if no conditions are specified)
         */
        public function find($conditions ='')
        {
            return $this->_mapper->find($conditions);
        }

        /**
         * Insert a new entity
         */
        public function insert($entity)
        {
            return $this->_mapper->insert($entity);
        }

        /**
         * Update an existing entity
         */
        public function update($entity, $col = 'id')
        {
            return $this->_mapper->update($entity, $col);
        }

        /**
         * Delete one or more entities
         */
        public function delete($entity, $col = 'id')
        {
            return $this->_mapper->delete($entity, $col);
        }

        /**
         * Delete one or more entities
         */
        public function getInsertId()
        {
            return $this->_mapper->getInsertId();
        }

    }