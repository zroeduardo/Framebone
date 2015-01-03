<?php

    namespace Packages\Blog\Model\Entry;

    use \Packages\Framebone\Database\Model\Mapper as Mapper,
        \Packages\Framebone\Database\Model\Library as Library,
        \Packages\Framebone\Database\Model\Proxy as Proxy,
        \Packages\Blog\Model\Comment as Comment;

    class EntryMapper extends Mapper\MapperAbstract
    {
        protected $_commentMapper;
        protected $_entityTable = 'entries';
        protected $_entityClass = '\Packages\Blog\Model\Entry\Entry';

        /**
         * Constructor
         */
        public function __construct(Library\AdapterInterface $adapter, Comment\CommentMapper $commentMapper)
        {
            $this->_commentMapper = $commentMapper;
            parent::__construct($adapter);
        }
       
        /**
         * Get the comment mapper
         */
        public function getCommentMapper()
        {
            return $this->_commentMapper;
        }

        /**
         * Delete an entry from the storage (deletes the related comments also)
         */
        public function delete($id, $col = 'id')
        {
            parent::delete($id);
            return $this->_commentMapper->delete($id, 'entry_id');
        }

        /**
         * Create an entry entity with the supplied data
         * (assigns a collection proxy to the 'comments' field for lazy-loading comments)
         */
        protected function _createEntity(array $data)
        {
            $entry = new $this->_entityClass(array(
                'id'       => $data['id'],
                'title'    => $data['title'],
                'content'  => $data['content'],
                'comments' => new Proxy\CollectionProxy($this->_commentMapper, "entry_id = {$data['id']}")
            ));
            return $entry;
        }
    }