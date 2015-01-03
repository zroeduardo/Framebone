<?php

    namespace Packages\Framebone\Database\Model\Proxy;

    use \Packages\Framebone\Database\Model as Model;

    class EntityProxy extends ProxyAbstract implements ProxyInterface
    {
        protected $_entity;

        /**
         * Load an entity via the mapper’s ‘findById()’ method
         */
        public function load()
        {
            if ($this->_entity === null) {
                $this->_entity = $this->_mapper->findById($this->_params);
                if (!$this->_entity instanceof Model\EntityAbstract) {
                    throw new \RuntimeException('Unable to load the related entity.');
                }
            }
            return $this->_entity;
        }
    }