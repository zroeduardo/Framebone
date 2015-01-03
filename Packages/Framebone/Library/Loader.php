<?php
    namespace Framebone\Library;

    \spl_autoload_extensions(".php,.fb"); // comma-separated list
    \spl_autoload_register(function($className) {

        $className = str_replace('\\', '/', $className);

        if(preg_match('/^[^A-Z]/', $className)) {
            // LoadTheClass
            spl_autoload("Packs/".preg_replace('/([a-z])([A-Z])/', '$1/$2', $className));
        } else {
            // Load_The_Class
            spl_autoload("Packs/".str_replace('/_/', '/', $className));
        }
    });
