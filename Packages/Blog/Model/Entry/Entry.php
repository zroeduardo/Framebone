<?php

    namespace Packages\Blog\Model\Entry;

    use \Packages\Framebone\Database\Model as Model,
        \Packages\Framebone\Database\Model\Proxy as Proxy;

    class Entry extends Model\EntityAbstract
    {
        protected $_allowedFields = array(
            'id',
            'title',
            'content',
            'comments'
        );

        /**
         * Set the entry ID
         */
        public function setId($id)
        {
            if(!filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 65535)))) {
                throw new \InvalidArgumentException('The entry ID is invalid.');
            }
            $this->_values['id'] = $id;
        }

        /**
         * Set the entry title
         */
        public function setTitle($title)
        {
            if (!is_string($title) || strlen($title) < 2 || strlen($title) > 32) {
                throw new \InvalidArgumentException('The title of the entry is invalid.');
            }
            $this->_values['title'] = $title;
        }

        /**
         * Set entry content
         */
        public function setContent($content)
        {
            if (!is_string($content) || empty($content)) {
                throw new \InvalidArgumentException('The entry is invalid.');
            }
            $this->_values['content'] = $content;
        }

        /**
         * Set the comments for the blog entry
         * (assigns a Collection Proxy for lazy-loading comments)
         */
        public function setComments(Proxy\CollectionProxy $comments)
        {
            $this->_values['comments'] = $comments;
        }
    }