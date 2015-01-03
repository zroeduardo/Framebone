<?php
    namespace Framebone\Library;

    class File
    {
        public static function loadJson($path, $assoc = false, $clear = false)
        {
            $json = file_get_contents($path);
            if($clear) {
                $json = str_replace(array("\n","\r"),"",$json);
                $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$json);
                $json = preg_replace('/(,)\s*}$/','}',$json);
            }
            return json_decode($json, $assoc); 
        }
    }