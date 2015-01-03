<?php

    namespace Packages\Framebone\Database\Model\Mapper;

    use \Packages\Framebone\Database\Model\Collection as Collection,
        \Packages\Framebone\Database\Model\Library as Library;

    abstract class MapperAbstract implements MapperInterface
    {
        protected $_adapter;
        protected $_entityTable;
        protected $_entityClass;
       
        /**
         * Constructor
         */
        public function __construct(Library\AdapterInterface $adapter, array $entityOptions = array())
        {
            $this->_adapter = $adapter;
            // set the entity table is the option has been specified
            if (isset($entityOptions['entityTable'])) {
                $this->setEntityTable($entityOtions['entityTable']);
            }
            // set the entity class is the option has been specified
            if (isset($entityOptions['entityClass'])) {
                $this->setEntityClass($entityOtions['entityClass']);
            }
            // check the entity options
            $this->_checkEntityOptions();
        }

        /**
         * Check if the entity options have been set
         */
        protected function _checkEntityOptions()
        {
            if (!isset($this->_entityTable)) {
                throw new \RuntimeException('The entity table has not been set yet.');
            }
            if (!isset($this->_entityClass)) {
                throw new \RuntimeException('The entity class has been not set yet.');
            }
        }

        /**
         * Get the database adapter
         */
        public function getAdapter()
        {
            return $this->_adapter;
        }

        /**
         * Set the entity table
         */
        public function setEntityTable($entityTable)
        {
            if (!is_string($table) || empty($entityTable)) {
                throw new \InvalidArgumentException('The entity table is invalid.');
            }
            $this->_entityTable = $entityTable;
            return $this;
        }

        /**
         * Get the entity table
         */
        public function getEntityTable()
        {
            return $this->_entityTable;
        }
       
        /**
         * Set the entity class
         */
        public function setEntityClass($entityClass)
        {
            if (!is_subclass_of($entityClass, 'BlogModelAbstractEntity')) {
                throw new \InvalidArgumentException('The entity class is invalid.');
            }
            $this->_entityClass = $entityClass;
            return $this;
        }

        /**
         * Get the entity class
         */
        public function getEntityClass()
        {
            return $this->_entityClass;
        }
       
        /**
         * Find an entity by its ID
         */
        public function findById($id)
        {
            if($this->tableExists($this->_entityTable) !== 0) {
                $this->_adapter->select($this->_entityTable, "id = '$id'");
                if ($data = $this->_adapter->fetch()) {
                    return $this->_createEntity($data);
                }
            }
            return null;
        }

        /**
         * Find an entity by its Cid
         */
        public function findByCid($cid)
        {
            if($this->tableExists($this->_entityTable) !== 0) {
                $this->_adapter->select($this->_entityTable, "cid = '$cid'");
                if ($data = $this->_adapter->fetch()) {
                    return $this->_createEntity($data);
                }
            }
            return null;
        }

        /**
         * Find entities according to the given criteria (all entities will be fetched if no criteria are specified)
         */
        public function find($conditions = '')
        {
            if($this->tableExists($this->_entityTable) !== 0) {
                $collection = new Collection\EntityCollection;
                $this->_adapter->select($this->_entityTable, $conditions);
                while ($data = $this->_adapter->fetch()) {
                    $collection[] = $this->_createEntity($data);
                }
            }
            
            return $collection;
        }

        /**
         * Insert a new entity in the storage
         */
        public function insert($entity)
        {
            if (!$entity instanceof $this->_entityClass) {
                throw new \InvalidArgumentException('The entity to be inserted must be an instance of ' . $this->_entityClass . '.');
            }
            return $this->_adapter->insert($this->_entityTable, $entity->toArray());
        }

        public function getInsertId()
        {
            return $this->_adapter->getInsertId();
        }
       
        /**
         * Update an existing entity in the storage
         */
        public function update($entity, $col = 'id')
        {
            if ($entity instanceof $this->_entityClass && isset($entity->$col)) {
                $id = $entity->$col;
                $entity = $entity->toArray();
                unset($entity[$col]);

                return $this->_adapter->update($this->_entityTable, $entity, "$col = '$id'");
            } else {
                throw new \InvalidArgumentException('The entity to be updated must be an instance of ' . $this->_entityClass . '.');
            }

        }

        /**
         * Delete one or more entities from the storage
         */
        public function delete(array $values, $col = 'id')
        {
            foreach ($values as $key => $value) {
                if ($value instanceof $this->_entityClass) {
                    $values[$key] = $entity->$col;
                }
            }
            return $this->_adapter->delete($this->_entityTable, "$col IN (".implode(',', $values).")");
        }

        /**
         * Check if a table exists
         */
        public function tableExists($table)
        {
            return $this->_adapter->tableExists($table);
        }

        /**
         * Reconstitute an entity with the data retrieved from the storage (implementation delegated to concrete mappers)
         */
        abstract protected function _createEntity(array $data);
    }