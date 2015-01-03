<?php

    namespace Packages\Framebone\Database\Model\Proxy;

    use \Packages\Framebone\Database\Model\Collection as Collection;

    class CollectionProxy extends ProxyAbstract implements ProxyInterface, \Countable, \IteratorAggregate
    {
        protected $_collection;

        /**
         * Load the entity collection via the mapper's 'find()' method
         */
        public function load()
        {
            if ($this->_collection === null) {
                $this->_collection = $this->_mapper->find($this->_params);
                if (!$this->_collection instanceof Collection\EntityCollection) {
                    throw new \RuntimeException('Unable to load the related collection.');
                }
            }
            return $this->_collection;
        }

        /**
         * Count the number of elements in the collection
         */
        public function count()
        {
            return count($this->load());
        }

        /**
         * Load the entity collection when the proxy is used in a 'foreach' construct
         */
        public function getIterator()
        {
            return $this->load();
        }
    }