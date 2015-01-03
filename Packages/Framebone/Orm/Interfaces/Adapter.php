<?php 
    namespace Packages\Framebone\Orm\Interfaces;

    /*//////////
        Framebone © 2014. Todos os direitos reservados a seus 
        respectivos proprietários.

        Author: Eduardo Segura
        E-mail: zro.eduardo@gmail.com
    /*/////////

    /*//////////
        Interface para os diversos tipos de banco de dados.
    /*/////////

    interface Adapter
    {
        // Conexão 
        public static function connect();
        public static function disconnect();
        // Ações com as estruturas da tabela
        public static function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = null);
        public static function create($table, array $data);
        public static function alter($table, array $data);
        public static function drop($table);
        public static function truncate($table, array $data);
        // Ações com os campos da tabela
        public static function insert($table, array $data);
        public static function update($table, array $data, $where);
        public static function delete($table, $where);
        // Execuçao 
        public static function query($query);
        // Outras ações
        public static function lastId();
        public static function countRows();
        public static function affectedRows();
        //function isTable();
    }