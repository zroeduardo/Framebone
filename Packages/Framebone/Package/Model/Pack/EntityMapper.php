<?php

    namespace Packages\Framebone\Package\Model\Pack;

    use \Packages\Framebone\Database\Model\Mapper as Mapper,
        \Packages\Framebone\Database\Model\Library as Library,
        \Packages\Framebone\Database\Model\Proxy as Proxy,
        \Packages\Framebone\Package\Model\Update as Update;

    class EntityMapper extends Mapper\MapperAbstract
    {
        protected $_updateMapper;
        protected $_entityTable = 'fb_pack';
        protected $_entityClass = '\Packages\Framebone\Package\Model\Pack\Entity';

        /**
         * Constructor
         */
        public function __construct(Library\AdapterInterface $adapter, Update\EntityMapper $updateMapper)
        {
            $this->_updateMapper = $updateMapper;
            parent::__construct($adapter);
        }

        /**
         * Get the author mapper
         */
        public function getUpdateMapper()
        {
            return $this->_updateMapper;
        }

        /**
         * Delete an log from the storage (deletes the related comments also)
         */
        public function delete(array $values, $col = 'id')
        {
            parent::delete($values, $col);
            return $this->_updateMapper->delete($values, 'id_pack');
        }

        /**
         * Create a comment entity with the supplied data
         * (assigns an entity proxy to the 'author' field for lazy-loading authors)
         */
        protected function _createEntity(array $data)
        {
            $pack = new $this->_entityClass(array(
                'id' => $data['id'],
                'cid' => $data['cid'],
                'version' => $data['version'],
                'title' => $data['title'],
                'description' => $data['description'],
                'gather' => $data['gather'],
                'dependency' => $data['dependency'],
                'incompatible' => $data['incompatible'],
                'updates' => new Proxy\CollectionProxy($this->_updateMapper, "id_pack = {$data['id']}")
            ));
            return $pack;
        }
    }