<?php

    namespace Packages\Blog\Model\Comment;

    use \Packages\Framebone\Database\Model as Model,
        \Packages\Framebone\Database\Model\Proxy as Proxy;

    class Comment extends Model\EntityAbstract
    {
        protected $_allowedFields = array(
            'id',
            'content',
            'author',
            'author_id',
            'entry_id'
        );

        /**
         * Set the comment ID
         */
        public function setId($id)
        {
            if(!filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 65535)))) {
                throw new \InvalidArgumentException('The comment ID is invalid.');
            }
            $this->_values['id'] = $id;
        }

        /**
         * Set the content for the comment
         */
        public function setContent($content)
        {
            if (!is_string($content) || strlen($content) < 2) {
                throw new \InvalidArgumentException('The comment is invalid.');
            }
            $this->_values['content'] = $content;
        }

        /**
         * Set the author of the comment
         * (assigns an entity proxy for lazy-loading authors)
         */
        public function setAuthor(Proxy\EntityProxy $author)
        {
            $this->_values['author'] = $author;
        }
    }