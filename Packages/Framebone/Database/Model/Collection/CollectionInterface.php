<?php

    namespace Packages\Framebone\Database\Model\Collection;

    use \Packages\Framebone\Database\Model as Model;

    interface CollectionInterface extends \Countable, \IteratorAggregate, \ArrayAccess
    {
        public function toArray();

        public function clear();

        public function reset();

        public function add($key, Model\EntityAbstract $entity);
       
        public function get($key);

        public function remove($key);

        public function exists($key);
    }