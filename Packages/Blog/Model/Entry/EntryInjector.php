<?php

    namespace Packages\Blog\Model\Entry;

    use \Packages\Framebone\Database\Model\Library as Library,
        \Packages\Framebone\Database\Model\Mapper as Mapper,
        \Packages\Framebone\Database\Injector as Injector,
        \Packages\Framebone\Database\Service as Service,
        \Packages\Blog\Model\Comment as Comment,
        \Packages\Blog\Model\Author as Author;

    class EntryInjector implements Injector\InjectorInterface
    {
        /**
         * Create the entry service
         */
        public function create()
        {
            $mysqlInjector = new Injector\MysqlAdapterInjector;
            $mysqlAdapter = $mysqlInjector->create();
            return new Service\EntityService(
                new EntryMapper(
                    $mysqlAdapter, new Comment\CommentMapper(
                        $mysqlAdapter, new Author\AuthorMapper($mysqlAdapter)
                    )
                )
            ); 
        }
    }