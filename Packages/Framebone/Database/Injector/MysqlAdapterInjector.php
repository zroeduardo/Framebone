<?php

    namespace Packages\Framebone\Database\Injector;

    use \Packages\Framebone as Framebone,
        \Packages\Framebone\Database\Model\Library as Library;

    class MysqlAdapterInjector implements InjectorInterface
    {
        protected static $_mysqlAdapter;

        /**
         * Create an instance of the MysqlAdapter class
         */
        public function create()
        {
            if (self::$_mysqlAdapter === null) {
               self::$_mysqlAdapter = new Library\MysqlAdapter(Framebone\Stack::fetch(array('database'))['database']);
            }
            return self::$_mysqlAdapter;
        }
    }