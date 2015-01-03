<?php

    namespace Packages\Blog\Model\Comment;

    use \Packages\Framebone\Database\Model\Mapper as Mapper,
        \Packages\Framebone\Database\Model\Library as Library,
        \Packages\Framebone\Database\Model\Proxy as Proxy,
        \Packages\Blog\Model\Author as Author;

    class CommentMapper extends Mapper\MapperAbstract
    {
        protected $_authorMapper;
        protected $_entityTable = 'comments';
        protected $_entityClass = '\Packages\Blog\Model\Comment\Comment';

        /**
         * Constructor
         */
        public function __construct(Library\AdapterInterface $adapter, Author\AuthorMapper $authorMapper)
        {
            $this->_authorMapper = $authorMapper;
            parent::__construct($adapter);
        }

        /**
         * Get the author mapper
         */
        public function getAuthorMapper()
        {
            return $this->_authorMapper;
        }

        /**
         * Create a comment entity with the supplied data
         * (assigns an entity proxy to the 'author' field for lazy-loading authors)
         */
        protected function _createEntity(array $data)
        {
            $comment = new $this->_entityClass(array(
                'id'       => $data['id'],
                'content'  => $data['content'],
                'author'   => new Proxy\EntityProxy($this->_authorMapper, $data['author_id'])
            ));
            return $comment;
        }
    }