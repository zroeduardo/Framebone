<?php

    namespace Packages\Blog\Model\Author;

    use \Packages\Framebone\Database\Model as Model;

    class Author extends Model\EntityAbstract
    {
        protected $_allowedFields = array(
            'id',
            'name',
            'email'
        );

        /**
         * Set the author ID
         */
        public function setId($id)
        {
            if(!filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 65535)))) {
                throw new \InvalidArgumentException('The ID of the author is invalid.');
            }
            $this->_values['id'] = $id;
        }

        /**
         * Set the author's name
         */
        public function setName($name)
        {
            if (!is_string($name) || strlen($name) < 2) {
                throw new \InvalidArgumentException('The name of the author is invalid.');
            }
            $this->_values['name'] = $name;
        }

        /**
         * Set the author's email
         */
        public function setEmail($email)
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('The email address of the author is invalid.');
            }
            $this->_values['email'] = $email;
        }
    }