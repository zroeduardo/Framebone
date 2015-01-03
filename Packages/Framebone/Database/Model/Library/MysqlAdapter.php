<?php

    namespace Packages\Framebone\Database\Model\Library;

    class MysqlAdapter implements AdapterInterface
    {
        protected $_config = array();
        protected $_link;
        protected $_result;
       
        /**
         * Constructor
         */
        public function __construct(array $config)
        {
            if (count($config) < 5) {
                throw new \InvalidArgumentException('Invalid number of '. count($config) .' connection parameters.');
            }
            $this->_config = $config;
        }
       
        /**
         * Connect to MySQL
         */
        public function connect()
        {
            // connect only once
            if ($this->_link === null) {
                if (!$this->_link = @mysqli_connect($this->_config['host'], $this->_config['user'], $this->_config['password'], $this->_config['database'])) {
                    throw new \RuntimeException('Error connecting to the server : ' . mysqli_connect_error());
                }
                unset($host, $user, $password, $database);
            }
            return $this->_link;
        }

        /**
         * Execute the specified query
         */
        public function query($query)
        {
            if (!is_string($query) || empty($query)) {
                throw new \InvalidArgumentException('The specified query is not valid.');
            }
            // lazy connect to MySQL
            $this->connect();
            if (!$this->_result = mysqli_query($this->_link, $query)) {
                throw new \RuntimeException('Error executing the specified query ' . $query . mysqli_error($this->_link));
            }
            return $this->_result;
        }
       
        /**
         * Perform a SELECT statement
         */
        public function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = null)
        {
            $query = 'SELECT ' . $fields . ' FROM ' . $this->_config['prefix'].$table
                   . (($where) ? ' WHERE ' . $where : '')
                   . (($limit) ? ' LIMIT ' . $limit : '')
                   . (($offset && $limit) ? ' OFFSET ' . $offset : '')
                   . (($order) ? ' ORDER BY ' . $order : '');
            $this->query($query);
            return $this->countRows();
        }

        /**
         * Check if a table exists
         */
        public function tableExists($table)
        {
            $query = "SHOW TABLES LIKE '".$this->_config['prefix'].$table."'";
            $this->query($query);
            return $this->countRows();
        }

        /**
         * Perform an CREATE statement
         */
        public function create($table, array $data)
        {
            if($this->tableExists($table) === 0)
            {
                $fields = implode(',', $data);
                $query = 'CREATE TABLE '. $this->_config['prefix'].$table . ' (' . $fields . ') ';
                return $this->query($query);
            }
            return null;
        }

        /**
         * Perform an ALTER statement
         */
        public function alter($table, array $data)
        {
            if($this->tableExists($table) !== 0)
            {
                foreach ($data as $key => $field) {
                    if(stripos($field,'RENAME TO') !== false) {
                         $data[$key] = 'RENAME TO '.$this->_config['prefix'].substr($field, 10);
                    }
                }

                $fields = implode(',', $data);
                $query = 'ALTER TABLE '. $this->_config['prefix'].$table . ' ' . $fields .';';
                return $this->query($query);
            }
            return null;
        }

        /**
         * Perform an DROP statement
         */
        public function drop($table)
        {
            if($this->tableExists($table) !== 0)
            {
                $query = 'DROP TABLE '. $this->_config['prefix'].$table;
                return $this->query($query);
            }
            return null;
        }

        /**
         * Perform an TRUNCATE statement
         */
        public function truncate($table)
        {
            if($this->tableExists($table) !== 0)
            {
                $query = 'TRUNCATE TABLE '. $this->_config['prefix'].$table;
                return $this->query($query);
            }
            return null;
        }
       
        /**
         * Perform an INSERT statement
         */ 
        public function insert($table, array $data)
        {
            if($this->tableExists($table) !== 0) {
                $fields = implode(',', array_keys($data));
                $values = implode(',', array_map(array($this, 'quoteValue'), array_values($data)));
                $query = 'INSERT INTO ' . $this->_config['prefix'].$table . ' (' . $fields . ') ' . ' VALUES (' . $values . ')';
                $this->query($query);
                return $this->getInsertId();
            }
            return null;
        }
       
        /**
         * Perform an UPDATE statement
         */
        public function update($table, array $data, $where = '')
        {
            if($this->tableExists($table) !== 0) {
                $set = array();
                foreach ($data as $field => $value) {
                    $set[] = $field . '=' . $this->quoteValue($value);
                }
                $set = implode(',', $set);
                $query = 'UPDATE ' . $this->_config['prefix'].$table . ' SET ' . $set
                       . (($where) ? ' WHERE ' . $where : '');
                $this->query($query);
                return $this->getAffectedRows(); 
            }
            return 0;
        }
       
        /**
         * Perform a DELETE statement
         */
        public function delete($table, $where = '')
        {
            if($this->tableExists($table) !== 0) {
                $query = 'DELETE FROM ' . $this->_config['prefix'].$table
                       . (($where) ? ' WHERE ' . $where : '');
                $this->query($query);
                return $this->getAffectedRows();
            }
            return 0;
        }
       
        /**
         * Escape the specified value
         */
        public function quoteValue($value)
        {
            $this->connect();
            if ($value === null) {
                $value = 'NULL';
            }
            else if (!is_numeric($value)) {
                $value = "'" . mysqli_real_escape_string($this->_link, $value) . "'";
            }
            return $value;
        }
       
        /**
         * Fetch a single row from the current result set (as an associative array)
         */
        public function fetch()
        {
            if ($this->_result !== null) {
                if (($row = mysqli_fetch_array($this->_result, MYSQLI_ASSOC)) === false) {
                    $this->freeResult();
                }
                return $row;
            }
            return false;
        }

        /**
         * Get the insertion ID
         */
        public function getInsertId()
        {
            return $this->_link !== null
                ? mysqli_insert_id($this->_link) : null; 
        }
       
        /**
         * Get the number of rows returned by the current result set
         */ 
        public function countRows()
        {
            return $this->_result !== null
                ? mysqli_num_rows($this->_result) : 0;
        }
       
        /**
         * Get the number of affected rows
         */
        public function getAffectedRows()
        {
            return $this->_link !== null
                ? mysqli_affected_rows($this->_link) : 0;
        }
       
        /**
         * Free up the current result set
         */
        public function freeResult()
        {
            if ($this->_result === null) {
                return false;
            }
            mysqli_free_result($this->_result);
            return true;
        }
       
        /**
         * Close explicitly the database connection
         */
        public function disconnect()
        {
            if ($this->_link === null) {
                return false;
            }
            mysqli_close($this->_link);
            $this->_link = null;
            return true;
        }
       
        /**
         * Close automatically the database connection when the instance of the class is destroyed
         */
        public function __destruct()
        {
            $this->disconnect();
        }
    }