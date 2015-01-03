<?php
    namespace Packages\Framebone\Orm\Libraries;

    use \Packages\Framebone\Interfaces as Interfaces;

    /*//////////
        Framebone © 2014. Todos os direitos reservados a seus 
        respectivos proprietários.

        Author: Eduardo Segura
        E-mail: zro.eduardo@gmail.com
    /*/////////

    /*//////////
        Classe para utilização do banco de dados Mysql.
    /*/////////

    class Mysql implements Interfaces\Adapter
    {
        protected $_dbConfig;
        protected $_dbConnection;
        protected $_dbResult;

        /*//////////
            Construtor da classe, deve conter os parametros de conexão ao banco de dados
        @return
            void
        /*/////////
        public function __construct(array $db)
        {
            if (count($db) < 5) {
                throw new \InvalidArgumentException('Invalid number of '. count($db) .' connection parameters.');
            }
            $this->_dbConfig = $db;
        }

        /*//////////
            Destrutor da classe, encerra a conexão com o banco de dados.
        @return
            void
        /*/////////
        public function __destruct()
        {
            $this->disconnect();
        }

        /*//////////
            Conecta com o banco de dados. Durante a execução do php efetua apenas
            uma única conexão.
        @return
            object
        /*/////////
        public function connect()
        {
            if ($this->_dbConnection === null) {
                if (!$this->_dbConnection = @mysqli_connect($this->_dbConfig['host'], $this->_dbConfig['user'], $this->_dbConfig['password'], $this->_dbConfig['database'])) {
                    throw new \RuntimeException('Error connecting to the server : ' . mysqli_connect_error());
                }
            }
            return $this->_dbConnection;
        }

        /*//////////
            Finaliza a conexão atual (se existir).
        @return
            bool
        /*/////////
        public function disconnect()
        {
            if($this->_dbConnection !== null) {
                mysqli_close($this->_dbConnection);
                $this->_dbConnection = null;
                return true;
            }
            return false;
        }

        /*//////////
            Retorna os registros do banco de dados.
        @return
            mix
        /*/////////
        public function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = null)
        {
            $query = 'SELECT ' . $fields . ' FROM ' . $this->_dbConfig['prefix'].$table
                    . (($where) ? ' WHERE ' . $where : '')
                    . (($limit) ? ' LIMIT ' . $limit : '')
                    . (($offset && $limit) ? ' OFFSET ' . $offset : '')
                    . (($order) ? ' ORDER BY ' . $order : '');
            $this->query($query);
            return $this->countRows();
        }

        /*//////////
            Cria uma nova tabela dentro do banco de dados. É inserido o 
            parametro "IF NOT EXISTS" na query evitando entradas repetidas.
        @return
            mix
        /*/////////
        public function create($table, array $data)
        {
            $query = 'CREATE TABLE IF NOT EXISTS'. $this->_dbConfig['prefix'].$table . ' (' . implode(',', $data) . ') ';
            return $this->query($query);
        }

        /*//////////
            Altera a estrutura da tabela. É adicionado o prefixo da tabela quando
            a condição RENAME TO existir.
        @return
            bool
        /*/////////
        public function alter($table, array $data)
        {
            foreach ($data as $key => $field) {
                if(stripos($field,'RENAME TO') !== false) {
                     $data[$key] = 'RENAME TO '.$this->_dbConfig['prefix'].substr($field, 10);
                }
            }

            $query = 'ALTER TABLE IF EXISTS'. $this->_dbConfig['prefix'].$table . ' ' . implode(',', $data) .';';
            return $this->query($query);
        }

        /*//////////
            Remove completamente uma tabela do banco de dados.
        @return
            bool
        /*/////////
        public function drop($table)
        {
            $query = 'DROP TABLE IF EXISTS'. $this->_dbConfig['prefix'].$table;
            return $this->query($query);
        }

        /*//////////
            Deleta os registros da tabela.
            Aceita apenas os parametros ORDER BY e LIMIT
        @return
            bool
        /*/////////
        public function truncate($table, array $data)
        {
            $query = 'TRUNCATE TABLE IF EXISTS'. $this->_dbConfig['prefix'].$table . ' ' . implode("\n", $data) .';';
            return $this->query($query);
        }

        /*//////////
            Retorna os registros do banco de dados.
        @return
            mix
        /*/////////
        public function insert($table, array $data)
        {
            $fields = implode(',', array_keys($data));
            $values = implode(',', array_map(array($this, 'escape'), array_values($data)));
            
            $query = 'INSERT INTO ' . $this->_dbConfig['prefix'].$table . ' (' . $fields . ') ' . ' VALUES (' . $values . ')';
            $this->query($query);
            return $this->lastId();
        }

        /*//////////
            Atualiza os registros do banco de dados.
        @return
            mix
        /*/////////
        public function update($table, array $data, $where)
        {
            $set = array();
            foreach ($data as $field => $value) {
                $set[] = $field . '=' . $this->escape($value);
            }
            $set = implode(',', $set);
            $query = 'UPDATE ' . $this->_dbConfig['prefix'].$table . ' SET ' . $set
                   . (($where) ? ' WHERE ' . $where : '');
            $this->query($query);
            return $this->affectedRows();
        }

        /*//////////
            Exclui os registros do banco de dados.
        @return
            mix
        /*/////////
        public function delete($table, $where)
        {
            $query = 'DELETE FROM ' . $this->_dbConfig['prefix'].$table
                   . (($where) ? ' WHERE ' . $where : '');
            $this->query($query);
            return $this->affectedRows();
        }

        /*//////////
            Executa a query
        @return
            object || bool
        /*/////////
        public function query($query)
        {
            if (!is_string($query) || empty($query)) {
                throw new \InvalidArgumentException('The specified query is not valid.');
            }
            // lazy connect to MySQL
            $this->connect();
            if (!$this->_dbResult = mysqli_query($this->_dbConnection, $query)) {
                throw new \RuntimeException('Error executing the specified query ' . $query . mysqli_error($this->_dbConnection));
            }
            return $this->_dbResult;
        }

        /*//////////
            Retorna a última ID inserida nesta conexão.
        @return
            numeric || null
        /*/////////
        function lastId()
        {
            return ($this->_dbConnection !== null) ? mysqli_insert_id($this->_dbConnection) : null; 
        }

        /*//////////
            Retorna a quantidade de registros retornados.
        @return
            int
        /*/////////
        function countRows()
        {
            return ($this->_dbResult !== null) ? mysqli_num_rows($this->_dbResult) : 0;
        }

        /*//////////
            Retorna a quantidade de registros alterados.
        @return
            int
        /*/////////
        function affectedRows()
        {
            return ($this->_dbConnection !== null) ? mysqli_affected_rows($this->_dbConnection) : 0;
        }

        /*//////////
            Checa se uma tabela existe.
        @return
            bool
        /*/////////
        // function isTable($table)
        // {
        //     $query = "SHOW TABLES LIKE '".$this->_dbConfig['prefix'].$table."'";
        //     if($this->query($query))
        //     {
        //         return true;
        //     }
        //     return false;
        // }

        /*//////////
            Transforma o valor para uma string válida.
        @return
            bool
        /*/////////
        public function escape($value)
        {
            $this->connect();
            if ($value === null) {
                $value = 'NULL';
            }
            else if (!is_numeric($value)) {
                $value = "'" . mysqli_real_escape_string($this->_dbConnection, $value) . "'";
            }
            return $value;
        }
    }