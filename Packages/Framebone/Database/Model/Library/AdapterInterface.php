<?php

    namespace Packages\Framebone\Database\Model\Library;

    interface AdapterInterface
    {
        function connect();
       
        function disconnect(); 
       
        function query($query);
       
        function fetch(); 
       
        function select($table, $conditions = '', $fields = '*', $order = '', $limit = null, $offset = null);

        function create($table, array $data);
       
        function insert($table, array $data);
       
        function update($table, array $data, $conditions);
       
        function delete($table, $conditions);
       
        function getInsertId();
       
        function countRows();
       
        function getAffectedRows();
    }