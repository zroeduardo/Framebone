<?php
    namespace Framebone\Library;

    /*//////////
        Framebone © 2014. Todos os direitos reservados a seus 
        respectivos proprietários.

        Author: Eduardo Segura
        E-mail: zro.eduardo@gmail.com
    /*/////////

    /*//////////
        Esta classe guarda todas as variáveis usada no sistema
        e tem a função de gerenciar todo o processo de inclusão,
        alteração, leitura e acesso dos valores.
    /*/////////

    class Stack
    {
        private static $STACK__;

        /*//////////
            Função para debbug do stack.
        /*/////////
        public static function debug()
        {
            print "<pre>";
                var_dump(self::$STACK__);
            print "</pre>";
        }

        /*//////////
            Adiciona a variavel diretamente no array.
        @params
            string  $type
            string  $variable
            mix     $value
        /*/////////
        private static function set($type, $variable, $value)
        {
            self::$STACK__['$'.$variable]['&'.$type] = $value;
        }

        /*//////////
            Controla o acesso as variáveis dentro do stack.
        @params
            string  $variable
            string  $deny
            array   $options
        @return
            bool
        /*/////////
        private static function denied($variable, $deny, $options)
        {
            // Checamos se há alguma restrição
            if(isset(self::$STACK__['$'.$variable]['&'.$deny]))
            {
                // 1 Checamos se precisamos de uma senha
                // 2 Checamos se possuímos a senha
                // 3 Checamos se as senhas são iguais
                if( isset(self::$STACK__['$'.$variable]['&p']) &&
                    isset($options['pass']) && 
                    self::$STACK__['$'.$variable]['&p'] === $options['pass']
                    ||
                    isset(self::$STACK__['$'.$variable]['&h']) &&
                    isset($options['hash']) &&
                    password_verify($options['hash'], self::$STACK__['$'.$variable]['&h']) )
                {
                    return false;
                }

                return true;
            }

            return false;
        }

        /*//////////
            Checa se uma variável existe no stack
        @params
            string  $variable
        @return
            bool
        /*/////////
        private static function exist($variable)
        {
            if(isset(self::$STACK__['$'.$variable]))
            {
                return true;
            }

            return false;
        }

        /*//////////
            Adiciona uma variável no stack.
        @params
            string  $variable
            mix     $value
            array   $options = [
                        array   deny = ['w','r', 'u']
                        string  pass = ['code']
                        string  hash = ['code', 'cost']
                    ]
        @return
            bool
        /*/////////
        private static function addToStack($variable, $value, $options = [])
        {
            // Checamos se a variável não existe ou se temos acesso a ela
            if( !self::exist($variable) || !self::denied($variable, 'w', $options) )
            {
                // Primeira salvamos o valor 
                self::set('v', $variable, $value);

                // Configuramos o acesso
                if(isset($options['deny']) && array($options['deny'])) {
                    foreach ($options['deny'] as $key => $value) {
                        self::set($value, $variable, true);
                    }
                }

                // Configuramos se a senha simples de acesso 
                if(isset($options['pass']['code'])) {
                    self::set('p', $variable, $options['pass']['code']);
                }

                // Configuramos se a senha simples de acesso 
                if(isset($options['hash']['code'])) {
                    $cost = 8;
                    if(isset($options['hash']['cost']))
                    {
                        $cost = $options['hash']['cost'];
                    }
                    self::set('h', $variable, password_hash($options['hash']['code'], PASSWORD_BCRYPT, ["cost" => $cost]));
                }
                return true;
            }
            return false;
        }

        /*//////////
            Removemos uma variável do stack.
        @params
            string  $variable
            array   $options = [
                        string  pass
                        string  hash
                    ]
        @return
            bool
        /*/////////
        private static function removeFromStack($variable, $options = [])
        {
            // Checmoas se a variável existe e se temos o acesso para remove-la
            if( self::exist($variable) && !self::denied($variable, 'u', $options) && !self::denied($variable, 'w', $options) ) {
               unset(self::$STACK__['$'.$variable]);
               return true;
            }

            return false;
        }

        /*//////////
            Retorna uma lista de valore de uma variável do stack.
        @params
            array   $variable
            array   $options = [
                        string  pass
                        string  hash
                    ] 
        @return
            mix
            bool
        /*/////////
        private static function fetchFromStack($variable, $options = [])
        {
            // Checamos se a variável existe e se temos acesso ao seu valor
            if(self::exist($variable) && !self::denied($variable, 'r', $options)) {
                return self::$STACK__['$'.$variable]['&v'];
            }

            return false;
        }

        /*//////////
            Adiciona uma lista de variáveis no stack.
        @params
            array   $array
            array   $options = [
                        array   deny = ['w','r', 'u']
                        string  pass
                        string  hash
                        bool    return
                    ]
        @return
            bool
            array
        /*/////////
        public static function add($array, $options = [])
        {
            if(is_array($array) && is_array($options))
            {
                // Checamos se temos que informar cada resultado.
                $return = (isset($options['return']) && $options['return'] == true);

                foreach ($array as $key => $value)
                {
                    $added = self::addToStack($key, $value, $options);

                    // Guardamos dentro do próprio array o resultado.
                    if($return) {
                        $array[$key] = $added;
                    }
                }

                // Retorno da array com os resultados.
                if($return) {
                    return $array;
                }

            }

            return false;
        }

        /*//////////
            Removemos uma variável do stack.
        @params
            array   $array
            array   $options = [
                        string  pass
                        string  hash
                        bool    return
                    ]
        @return
            bool
            array
        /*/////////
        public static function remove($array, $options = []) 
        {
            if(is_array($array) && is_array($options))
            {
                // Checamos se temos que informar cada resultado.
                $return = (isset($options['return']) && $options['return'] == true);

                foreach ($array as $key => $variable)
                {
                    $removed = self::removeFromStack($variable, $options);

                    // Guardamos dentro do próprio array o resultado.
                    if($return) {
                        $array[$key] = $removed;
                    }
                }

                // Retorno da array com os resultados.
                if($return) {
                    return $array;
                }

            }

            return false;
        }

        /*//////////
            Retorna o valor de uma variável do stack.
        @params
            array   $variable
            array   $options = [
                        string  pass
                        string  hash
                    ]
        @return
            mix
            bool
        /*/////////
        public static function fetch($array = [], $options = [])
        {
            if(is_array($options)) {

                if(is_array($array)) {

                    $return = [];

                    if(empty($array)) {
                        $array = self::$STACK__;
                        $options['all'] = true;
                    }

                    foreach ($array as $key => $variable) {
                        // Removemos o & da variável
                        if(isset($options['all'])) {
                            $variable = substr($key, 1);
                        }

                        if($fetched = self::fetchFromStack($variable, $options)) {
                            $return[$variable] = $fetched;
                        }
                    }

                    if(!empty($return)) {
                        return $return;
                    }

                } else {
                    if($fetched = self::fetchFromStack($array, $options)) {
                        return $fetched;
                    }
                }
            }

            return false;
        }
    }