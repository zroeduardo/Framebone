<?php

    namespace Packages\Framebone\Database\Service;

    use \Packages\Framebone\Database\Injector as Injector;

    class ServiceLocator
    {
        protected $_injectors = array();
        protected $_services = array();
       
        /**
         * Add a single injector
         */
        public function addInjector($key, Injector\InjectorInterface $injector)
        {
            if (!isset($this->_injectors[$key])) {
                $this->_injectors[$key] = $injector;
                return true;
            }
            return false;
        }
       
        /**
         * Add multiple injectors
         */
        public function addInjectors(array $injectors)
        {
            foreach ($injectors as $key => $injector) {
                $this->addInjector($key, $injector);
            }
            return $this;
        }
       
        /**
         * Check if the specified injector exists
         */
        public function injectorExists($key)
        {
            return isset($this->_injectors[$key]);
        }
       
        /**
         * Get the specified injector
         */
        public function getInjector($key)
        {
            return isset($this->_injectors[$key])
                ? $this->_injectors[$key] : null;
        }
       
        /**
         * Add a single service
         */
        public function addService($key, ServiceAbstract $service)
        {
            if (!isset($this->_services[$key])) {
                $this->_services[$key] = $service;
                return true;
            }
            return false;
        }
       
        /**
         * Add multiple services
         */
        public function addServices(array $services)
        {
            foreach ($services as $key => $service) {
                $this->addService($key, $service);
            }
            return $this;
        }
       
        /**
         * Check if the specified service exists
         */
        public function serviceExists($key)
        {
            return isset($this->_services[$key]);
        }
       
        /**
         * Get the specified service.
         * If the service has been previously injected or created, get it from the $_services array;
         * Otherwise, make the associated injector create the service and save it to the $_services array
         */
        public function getService($key)
        {
            if (isset($this->_services[$key])) {
                return $this->_services[$key];
            }
            if (!isset($this->_injectors[$key])) {
                throw new \RuntimeException('The specified service cannot be created because the associated injector does not exist.');
            }
            $service = $this->getInjector($key)->create();
            $this->addService($key, $service);
            return $service;
        }
    }