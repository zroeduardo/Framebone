<?php

    namespace Packages\Framebone\Package\Model\Log;

    use \Packages\Framebone\Database\Model as Model;

    class Entity extends Model\EntityAbstract
    {
        protected $_allowedFields = array(
            'id',
            'id_update',
            'description',
            'author',
            'email',
        );

        /**
         * Set the author ID
         */
        public function setId($id)
        {
            if(!$this->_checkInt($id)) {
                
                throw new \InvalidArgumentException('The ID of the log is invalid.');
            }
            $this->_values['id'] = $id;
        }

        /**
         * Set the author's name
         */
        public function setAuthor($author)
        {
            if (!$this->_checkString($author) && !empty($author)) {
                throw new \InvalidArgumentException('The name of the author is invalid.');
            }
            $this->_values['author'] = $author;
        }

        /**
         * Set the author's email
         */
        public function setEmail($email)
        {
            if (!$this->_checkEmail($email) && !empty($email)) {
                throw new \InvalidArgumentException('The email address of the author is invalid.');
            }
            $this->_values['email'] = $email;
        }

        /**
         * Set entry content
         */
        public function setDescription($description)
        {
            if (!$this->_checkString($description)) {
                throw new \InvalidArgumentException('The description is invalid.');
            }
            $this->_values['description'] = $description;
        }
    }