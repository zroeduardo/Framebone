<?php

    namespace Packages\Framebone\Package\Model\Log;

    use \Packages\Framebone\Database\Injector as Injector,
        \Packages\Framebone\Database\Service as Service;

    class EntityInjector implements Injector\InjectorInterface
    {
        /**
         * Create the entry service
         */
        public function create()
        {
            $mysqlInjector = new Injector\MysqlAdapterInjector;
            $mysqlAdapter = $mysqlInjector->create();
            return new Service\EntityService(
                new EntityMapper($mysqlAdapter)
            ); 
        }
    }