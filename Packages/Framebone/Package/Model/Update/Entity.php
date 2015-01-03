<?php

    namespace Packages\Framebone\Package\Model\Update;

    use \Packages\Framebone\Database\Model as Model,
        \Packages\Framebone\Database\Model\Proxy as Proxy;

    class Entity extends Model\EntityAbstract
    {
        protected $_allowedFields = array(
            'id',
            'id_pack',
            'number',
            'date',
            'description',
            'logs'
        );

        /**
         * Set the entry ID
         */
        public function setId($id)
        {
            if(!$this->_checkInt($id)) {
                throw new \InvalidArgumentException('The Update ID is invalid.');
            }
            $this->_values['id'] = $id;
        }

        /**
         * Set entry content
         */
        public function setDate($date)
        {
            if (!$this->_checkString($date)) {
                throw new \InvalidArgumentException('The Update Date is invalid.');
            }
            $this->_values['date'] = $date;
        }

        /**
         * Set entry content
         */
        public function setDescription($description)
        {
            if (!$this->_checkString($description)) {
                throw new \InvalidArgumentException('The Update Description is invalid.');
            }
            $this->_values['description'] = $description;
        }

         /**
         * Set the entry ID
         */
        public function setUpdate($update)
        {
            if(!$this->_checkInt($update)) {
                throw new \InvalidArgumentException('The Update Number is invalid.');
            }
            $this->_values['update'] = $update;
        }

        /**
         * Set entry content
         */
        public function setNumber($number)
        {
            if (!$this->_checkInt($number)) {
                throw new \InvalidArgumentException('The Updated Number is invalid.');
            }
            $this->_values['number'] = $number;
        }

        /**
         * Set the entry ID
         */
        public function setId_pack($id_pack)
        {
            if(!$this->_checkInt($id_pack)) {
                throw new \InvalidArgumentException('The Update Pack ID is invalid.');
            }
            $this->_values['id_pack'] = $id_pack;
        }


        /**
         * Set the comments for the blog entry
         * (assigns a Collection Proxy for lazy-loading comments)
         */
        public function setLogs(Proxy\CollectionProxy $logs)
        {
            $this->_values['logs'] = $logs;
        }
    }