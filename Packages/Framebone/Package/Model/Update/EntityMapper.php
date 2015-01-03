<?php

    namespace Packages\Framebone\Package\Model\Update;

    use \Packages\Framebone\Database\Model\Mapper as Mapper,
        \Packages\Framebone\Database\Model\Library as Library,
        \Packages\Framebone\Database\Model\Proxy as Proxy,
        \Packages\Framebone\Package\Model\Log as Log;

    class EntityMapper extends Mapper\MapperAbstract
    {
        protected $_logMapper;
        protected $_entityTable = 'fb_pack_update';
        protected $_entityClass = '\Packages\Framebone\Package\Model\Update\Entity';

        /**
         * Constructor
         */
        public function __construct(Library\AdapterInterface $adapter, Log\EntityMapper $logMapper)
        {
            $this->_logMapper = $logMapper;
            parent::__construct($adapter);
        }

        /**
         * Get the author mapper
         */
        public function getLogMapper()
        {
            return $this->_logMapper;
        }

        /**
         * Delete an log from the storage (deletes the related comments also)
         */
        public function delete(array $values, $col = 'id')
        {
            // Achamos todos os updates
            $updates = $this->find("id_pack IN (".implode(",", $values).")");

            // Excluimos os updates
            parent::delete($values, $col);

            // Excluimos todos os logs
            if(!is_null($updates)){
                
                $deletes = null;

                foreach ($updates as $key => $update) {
                    $deletes[] = $update->id;
                }
                return $this->_logMapper->delete($deletes, 'id_update');
            }
            return null;
        }

        /**
         * Create a comment entity with the supplied data
         * (assigns an entity proxy to the 'author' field for lazy-loading authors)
         */
        protected function _createEntity(array $data)
        {
            $version = new $this->_entityClass(array(
                'id' => $data['id'],
                'id_pack' => $data['id_pack'],
                'number' => $data['number'],
                'date' => $data['date'],
                'description' => $data['description'],
                'logs' => new Proxy\CollectionProxy($this->_logMapper, "id_update = {$data['id']}")
            ));
            return $version;
        }
    }