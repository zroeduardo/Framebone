<?php

    namespace Packages\Blog\Model\Author;

    use \Packages\Framebone\Database\Model\Library as Library,
        \Packages\Framebone\Database\Model\Mapper as Mapper,
        \Packages\Framebone\Database\Injector as Injector,
        \Packages\Framebone\Database\Service as Service;

    class AuthorInjector implements Injector\InjectorInterface
    {
        /**
         * Create the entry service
         */
        public function create()
        {
            $mysqlInjector = new Injector\MysqlAdapterInjector;
            $mysqlAdapter = $mysqlInjector->create();
            return new Service\EntityService(new AuthorMapper($mysqlAdapter)); 
        }
    }