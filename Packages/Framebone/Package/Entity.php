<?php 
    
    namespace Packages\Framebone\Package;

    use \Packages\Framebone as Framebone,
        \Packages\Framebone\Database\Service as ServiceLocator,
        \Packages\Framebone\Package\Model\Pack as Pack,
        \Packages\Framebone\Package\Model\Update as Update,
        \Packages\Framebone\Package\Model\Log as Log;

    class Entity
    {
        protected $_adapter;
        protected $_version;

        protected $_serviceLocator;
        protected $_servicePack;
        protected $_serviceUpdate;
        protected $_serviceLog;

            protected $_pack;
            protected $_cid;
            protected $_suports;
            protected $_updates;

            protected $_idPack;
            protected $_idUpdate;
            protected $_idLogs;


        public function __construct($pack = null)
        {
            if(!is_null($pack)) {
                $this->_setPack($pack);
            }
            $this->_setService();
            $this->_setVersion();
        }

        private function _setPack($pack)
        {
            $pack = '\\Packages\\'.$pack.'\\Updates';
            $this->_pack = new $pack;
            
            $this->_getUpdates();
            $this->_clearIds();
        }

        private function _setAdapter()
        {
            if(is_null($this->_adapter)) {
                $this->_adapter = new \Packages\Framebone\Database\Model\Library\MysqlAdapter(Framebone\Stack::fetch(array('database'))['database']);
            }
        }

        private function _setService($service = null)
        {
            if(is_null($this->_serviceLocator)) {
                $this->_serviceLocator = new ServiceLocator\ServiceLocator;
            }

            if(!is_null($service)) {
                if(is_null($this->_servicePack) && $service == 'pack') {
                    $this->_serviceLocator->addInjector($service, new Pack\EntityInjector);
                    $this->_servicePack = $this->_serviceLocator->getService($service);
                } elseif(is_null($this->_serviceUpdate) && $service == 'update') {
                    $this->_serviceLocator->addInjector($service, new Update\EntityInjector);
                    $this->_serviceUpdate = $this->_serviceLocator->getService($service);
                } elseif(is_null($this->_serviceLog) && $service == 'log') {
                    $this->_serviceLocator->addInjector($service, new Log\EntityInjector);
                    $this->_serviceLog = $this->_serviceLocator->getService($service);
                }
            }
        }

        private function _setVersion()
        {
            if(is_null($this->_version)) {
                $this->_version = Framebone\Stack::fetch(array('system'))['system']['version'];
            }
        }

        private function _checkCid()
        {
            if(!is_string($this->_pack->_cid) || strlen($this->_pack->_cid) < 2 || strlen($this->_pack->_cid) > 400 ) {
                throw new \InvalidArgumentException('The cid of the Pack is invalid.');
            }

            return true;
        }

        private function _checkSuports()
        {
            if(is_array($this->_pack->_suports) && in_array($this->_version[0], $this->_pack->_suports)) {
                return true;
            }

            return false;
        }

        private function _clearIds()
        {
            $this->_idUpdate = null;
            $this->_idLogs = null;
        }

        private function _getUpdates()
        {
            $this->_updates = get_class_methods($this->_pack);
        }

        private function _getUpdate($update)
        {
            if($update) {

                if($update = $this->_checkUpdate($update)) {
                    
                    $method = 'update'.str_replace('.', '_', $update['number']);
                    
                    // Limpamos as variaves a cada nova iteração das atualizações.
                    $this->_clearIds();

                    $options = $this->_pack->$method();

                    if(!is_null($options)) {
                        $options['update']['number'] = $update['number'];
                    }

                    return $options;
                }
            }

            return false;
        }

        private function _createTables(array $tables)
        {
            $array = null;
            foreach ($tables as $table => $fields) {
                $array[$table] = $this->_adapter->create($table, $fields);
            }
            return $array;
        }

        private function _alterTables(array $tables)
        {
            $array = null;
            foreach ($tables as $table => $fields) {
                $array[$table] = $this->_adapter->alter($table, $fields);
            }
            return $array;
        }

        private function _truncateTables(array $tables)
        {
            $array = null;
            foreach ($tables as $table => $fields) {
                $array[$table] = $this->_adapter->truncate($table);
            }
            return $array;
        }

        private function _dropTables(array $tables)
        {
            $array = null;
            foreach ($tables as $table) {
                $array[$table] = $this->_adapter->drop($table);
            }
            return $array;
        }

        private function _insertFields(array $tables)
        {
            $array = null;
            foreach ($tables as $table => $fields) {
                $array[$table] = $this->_adapter->insert($table, $fields);
            }
            return null;
        }

        private function _updateFields(array $tables)
        {
            $where = '';
            $array = null;
            foreach ($tables as $table => $fields) {
                if(isset($fields['where'])) {
                    $where = $fields['where'];
                }
                $array[$table] = $this->_adapter->update($table, $fields, $where);
            }
            return $array;
        }

        private function _deleteFields(array $tables)
        {
            $where = '';
            $array = null;
            foreach ($tables as $table => $fields) {
                if(isset($fields['where'])) {
                    $where = $fields['where'];
                }
                $array[$table] = $this->_adapter->delete($table, $fields, $where);
            }
            return $array;
        }

        private function _crudTables(array $options)
        {
            $array = null;
            if(isset($options['database'])) {

                $this->_setAdapter();

                foreach ($options['database'] as $statement => $tables) {
                    switch ($statement) {
                        case 'create':
                            $array[$statement] = $this->_createTables($tables);
                            break;
                        case 'alter' :
                            $array[$statement] = $this->_alterTables($tables);
                            break;
                        case 'truncate' :
                            $array[$statement] = $this->_truncateTables($tables);
                            break;
                        case 'drop' :
                            $array[$statement] = $this->_dropTables($tables);
                            break;
                        case 'insert' :
                            $array[$statement] = $this->_insertFields($tables);
                            break;
                        case 'update' :
                            $array[$statement] = $this->_updateFields($tables);
                            break;
                        case 'delete' :
                            $array[$statement] = $this->_deleteFields($tables);
                            break;
                        default:
                            break;
                    }
                }
            }
            return $array;
        }

        private function _getPackOptions(array $options, $allValues = true)
        {
            $array = [];

            if($allValues) {
                $array = [
                    'version' => '',
                    'title' => '',
                    'description' => '',
                    'gather' => '',
                    'dependency' => '',
                    'incompatible' => '',
                ];
            }

            if(isset($options['pack'])) {
                foreach ($options['pack'] as $key => $value) {
                    switch ($key) {
                        case 'version' :
                            $array['version'] = implode(':', $value);
                            break;
                        case 'title' :
                            $array['title'] = $value;
                            break;
                        case 'description' :
                            $array['description'] = $value;
                            break;
                        case 'group' :
                            $array['group'] = $value;
                            break;
                        case 'dependency' :
                            if(!empty($value)) {
                                foreach ($value as $key => $dependency) {
                                    $array['dependency'] .=  implode(':', $dependency).';';
                                }
                            }
                            break;
                        case 'incompatible' :
                            if(!empty($value)) {
                                foreach ($value as $key => $incompatible) {
                                    $array['incompatible'] .=  implode(':', $incompatible).';';
                                }
                            }
                            break;
                        default:
                            break;
                    }
                }
            }

            return $array;
        }

        private function _getUpdateOptions(array $options)
        {
            $array = [
                'number' => '',
                'date' => '',
                'description' => '',
            ];

            if(isset($options['update'])) {
                foreach ($options['update'] as $key => $value) {
                    switch ($key) {
                        case 'number' :
                            $array['number'] = $value;
                            break;
                        case 'date' :
                            $array['date'] = $value;
                            break;
                        case 'description' :
                            $array['description'] = $value;
                            break;
                        default:
                            break;
                    }
                }
            }

            return $array;
        }

        private function _getLogOptions(array $options)
        {
             $array = [
                'description' => '',
                'author' => '',
                'email' => '',
            ];

            foreach ($options as $key => $value) {
                switch ($key) {
                    case 'description' :
                        $array['description'] = $value;
                        break;
                    case 'author' :
                        $array['author'] = $value;
                        break;
                    case 'email' :
                        $array['email'] = $value;
                        break;
                    default:
                        break;
                }
            }

            return $array;
        }

        private function _insertPack(array $options)
        {
            $this->_setService('pack');

            $vars = $this->_getPackOptions($options);
            $new = new Pack\Entity(array_merge(array('cid' => $this->_pack->_cid), $vars));
            $this->_idPack = $this->_servicePack->insert($new);
        }


        private function _insertUpdate(array $options)
        {
            $vars = $this->_getUpdateOptions($options);

            if(!empty($vars['number'])) {

                $this->_setService('pack');
                $this->_setService('update');

                $packEntity = $this->_servicePack->findByCid($this->_pack->_cid);
                $new = new Update\Entity(array_merge(array('id_pack' => $packEntity->id), $vars));
                $this->_idUpdate = $this->_serviceUpdate->insert($new);
            }

            return $this->_idUpdate;
        }

        private function _insertLogs(array $options)
        {
            if(!is_null($this->_idUpdate) && isset($options['update']['logs'])) {

                $this->_setService('log');

                foreach ($options['update']['logs'] as $key => $log) {
                    $vars = $this->_getLogOptions($log);
                    
                    if(!empty($vars['description'])) {
                        $new = new Log\Entity(array_merge(array('id_update' => $this->_idUpdate), $vars));
                        $this->_idLogs[] = $this->_serviceLog->insert($new);
                    }
                }
            }

            return $this->_idLogs;
        }

        private function _updatePack(array $options)
        {
            $this->_setService('pack');

            $pack = $this->_servicePack->findByCid($this->_pack->_cid);

            if(!is_null($pack)) {
                
                $varsOptions = $this->_getPackOptions($options, false);
                $varsPack = [
                    'cid' => $this->_pack->_cid,
                    'version' => $pack->version,
                    'title' => $pack->title,
                    'description' => $pack->description,
                    'dependency' => $pack->dependency,
                    'incompatible' => $pack->incompatible,
                ];

                $update = new Pack\Entity(array_merge($varsPack, $varsOptions));

                return $this->_servicePack->update($update, 'cid');
            }

            return null;
        }

        private function _deletePack()
        {
            $this->_setService('pack');
            $pack = $this->_servicePack->findByCid($this->_pack->_cid);

            if($this->_servicePack->delete(array($pack->id))) {
                return $pack->id;
            }

            return false;
        }

        private function _updates($options, $install = false)
        {
            $update = [];

            // Aplicamos as operações nas estruturas das tabelas
            $update['tables'] = $this->_crudTables($options);

            if($install) {
                // Criamos e Atualizamos o pack no banco de dados se necessário
                $update['id_pack'] = $this->_insertPack($options);
            } else {
                // Atualizamos o apck no banco de dados
                $update['id_pack'] = $this->_updatePack($options);
            }

            // Inserimos a nova atualização no banco de dados
            $update['id_update'] = $this->_insertUpdate($options);

            // Inserimos os logs da nova atualização no banco de dados.
            $update['id_logs'] = $this->_insertLogs($options);

            return $update;
        }

        public function install()
        {
            // Não há checkagem de instalação na base, todos os updates são instalados.
            if($this->_checkCid() && $this->_checkSuports() && !$this->_checkInstall()) {

                $install = true;
                $updates = false;

                // Carregamos cada update
                foreach ($this->_updates as $update) {
                    if($options = $this->_getUpdate($update)) {
                        $updates[$update] = $this->_updates($options, $install);
                        $install = false;
                    }
                }
                
                return $updates;
            }

            return false;
        }

        public function update()
        {
            if($this->_checkInstall() && $this->_checkUpdates()) {
                
                $updates = null;

                // Carregamos cada update
                foreach ($this->_updates as $update) {
                    if($options = $this->_getUpdate($update)) {
                        $updates[$update] = $this->_updates($options);
                    }
                }
                
                return $updates;
            }

            return false;
        }

        public function uninstall()
        {
            $this->_getUpdates();
            $deletion = false;

            if($this->_checkInstall()) {

                // Deletamos o pacote
                $deletion['id_pack'] = $this->_deletePack();

                // Deletamos as tabelas
                $drops['database']['drop'] = null;

                foreach ($this->_updates as $update) {

                    if($options = $this->_getUpdate($update)) {

                        if(isset($options['database'])) {

                            foreach ($options['database'] as $statement => $tables) {
                                foreach ($tables as $table => $fields) {
                                    switch ($statement) {
                                        case 'create':
                                            $drops['database']['drop'][$table] = $table;
                                            break;
                                        case 'alter':
                                            foreach ($fields as $field) {
                                                if(stripos($field,'RENAME TO') !== false) {
                                                    $newTable = substr($field, 10);
                                                    unset($drops['database']['drop'][$table]);
                                                    $drops['database']['drop'][$newTable] = $newTable;
                                                }
                                            }
                                            break;
                                        case 'drop':
                                            if(isset($drops['database']['drop'][$fields])) {
                                                unset($drops['database']['drop'][$fields]);
                                            }
                                            break;
                                        default:
                                            break;
                                    }
                                }
                            }
                        }
                    }
                }

                // Aplicamos as operações nas estruturas das tabelas
                if(!is_null($drops['database']['drop'])) {
                    $deletion['tables'] = $this->_crudTables($drops);
                }
            }
            return $deletion;
        }

        private function _checkUpdates()
        {
            // Carregamos o pacote do banco
            $pack = $this->_servicePack->findByCid($this->_pack->_cid);

            if(!is_null($pack)) {

                // Carregamos os updates efetuados
                $updated = $pack->updates->getValues(array('number'))['number'];

                if(!is_null($updated)) {

                    $updates = [];

                    // Montamos os updates
                    foreach ($updated as $key => $number) {
                        $updates[$key] = 'update'.str_replace('.', '_', $number);
                    }

                    // Atualizamos a lista de updates
                    $this->_getUpdates();

                    if(!empty($this->_updates = array_diff($this->_updates, $updates))) {

                        foreach ($this->_updates as $key => $update) {
                            if(!$this->_checkUpdate($update)) {
                                unset($this->_updates[$key]);
                            }
                        }

                        if(!empty($this->_updates)) {
                            return true;
                        }
                    }
                }
            }

            return false;
        }

        private function _checkUpdate($update)
        {
            $update = explode('_', $update);

            if(count($update) > 1) {

                $updateNumber = $update[1];
                $updateVersion = mb_substr($update[0],6,3,'UTF-8');
                $updateString = mb_substr($update[0],0,6,'UTF-8');

                // Check if this update is for this version
                if($updateString == 'update' && $this->_version[0] == $updateVersion) {

                    $update = $updateString.$updateVersion.'_'.$updateNumber;

                    if(method_exists($this->_pack, $update) && is_callable(array($this->_pack, $update))) {
                        return [
                            'version' => $updateVersion, 
                            'number' => $updateVersion.'.'.$updateNumber ];
                    }
                }
            }

            return false;
        }

        private function _checkInstall()
        {
            $this->_setService('pack');

            if(is_null($this->_servicePack->findByCid($this->_pack->_cid))) {
                return false;
            }

            return true;
        }

        public function check()
        {
            if($this->_checkInstall()) {
                if($this->_checkUpdates())
                {
                    return 1;
                }
                return 0;
            }
            return -1;
        }
    }