<?php

    namespace Packages\Framebone\Package\Model\Pack;

    use \Packages\Framebone\Database\Model as Model,
        \Packages\Framebone\Database\Model\Proxy as Proxy;

    class Entity extends Model\EntityAbstract
    {
        protected $_allowedFields = array(
            'id',
            'cid',
            'version',
            'title',
            'description',
            'gather',
            'dependency',
            'incompatible',
            'updates',
        );

        /**
         * Set the entry ID
         */
        public function setId($id)
        {
            if(!$this->_checkInt($id)) {
                throw new \InvalidArgumentException('The Pack ID is invalid.');
            }
            $this->_values['id'] = $id;
        }

        /**
         * Set entry content
         */
        public function setCid($cid)
        {
            if (!$this->_checkString($cid)) {
                throw new \InvalidArgumentException('The Pack Cid is invalid.');
            }
            $this->_values['cid'] = $cid;
        }

        /**
         * Set entry content
         */
        public function setVersion($version)
        {
            if (!$this->_checkString($version)) {
                throw new \InvalidArgumentException('The Pack Version is invalid.');
            }
            $this->_values['version'] = $version;
        }

        /**
         * Set entry content
         */
        public function setTitle($title)
        {
            if (!$this->_checkString($title)) {
                throw new \InvalidArgumentException('The Pack Title is invalid.');
            }
            $this->_values['title'] = $title;
        }

        /**
         * Set entry content
         */
        public function setDescription($description)
        {
            if (!$this->_checkString($description)) {
                throw new \InvalidArgumentException('The Pack Description is invalid.');
            }
            $this->_values['description'] = $description;
        }


        /**
         * Set the comments for the blog entry
         * (assigns a Collection Proxy for lazy-loading comments)
         */
        public function setUpdates(Proxy\CollectionProxy $updates)
        {
            $this->_values['updates'] = $updates;
        }
    }