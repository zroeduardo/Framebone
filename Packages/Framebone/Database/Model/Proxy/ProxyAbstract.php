<?php

    namespace Packages\Framebone\Database\Model\Proxy;

    use \Packages\Framebone\Database\Model\Mapper as Mapper;

    abstract class ProxyAbstract
    {
        protected $_mapper;
        protected $_params;

        /**
         * Constructor
         */
        public function __construct(Mapper\MapperAbstract $mapper, $params)
        {
            if (!is_string($params) || empty($params)) {
                throw new \InvalidArgumentException('The mapper parameters are invalid.');
            }
            $this->_mapper = $mapper;
            $this->_params = $params;
        }

        /**
         * Get the mapper
         */
        public function getMapper()
        {
            return $this->_mapper;
        }

        /**
         * Get the mapper parameters
         */
        public function getParams()
        {
            return $this->_params;
        }

        public function getValues(array $fields = array())
        {
            $entities = $this->load();

            if(!is_null($entities)) {
                return $this->_getFields($entities->toArray(), $fields);
            }

            return null;
        }

        private function _getFields($entities, $fields)
        {
            $array = [];

            foreach ($entities as $keyEntity => $entity) {

                // Se estiver vazio pegar todos os campos
                if(empty($fields)) {
                    $fields = $entity->_getFields();
                }

                foreach ($fields as $value) {
                    $array[$value][$keyEntity] = $entity->$value;
                }
            }

            return $array;
        }

        public function findById($id) {
            return $this->_mapper->findById($id);
        }

        public function findByCid($cid) {
            return $this->_mapper->findByCid($cid);
        }

        public function find($conditions = '') {
            if(empty($conditions)) {
                $conditions = $this->_params;
            }
            return $this->_mapper->find($conditions);
        }
    }