<?php

    namespace Packages\Framebone\Database\Service;

    use \Packages\Framebone\Database\Model\Mapper as Mapper;

    class EntityService extends ServiceAbstract
    {
        /**
         * Constructor
         */
        public function __construct(Mapper\MapperAbstract $mapper)
        {
            parent::__construct($mapper);
        }
    }