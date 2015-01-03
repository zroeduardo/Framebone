<?php

    namespace Packages\Framebone\Package\Abstract;

    use \Packages\Framebone as fb;

    abstract class Update implements fb\Package\Interface\Update
    {
        public $_cid;
        public $_suports;

        const PLUS = '+';
        const MINUS = '-';
        const EQUAL = '=';

        public function __construct()
        {
            // a id é obrigatoria.
            if(empty($this->_cid)) {
                throw new \InvalidArgumentException('The package have to be an Identification.'); 
            }

            // ao menos deve haver a versão suportada do sistema.
            if(empty($this->_suports)) {
                throw new \InvalidArgumentException('The package have to be at least one suported version.'); 
            }
        }
    }