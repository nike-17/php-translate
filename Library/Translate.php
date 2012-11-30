<?php

// Register autoloader
require_once dirname(__FILE__) . '/Translate/Autoloader.php';
Translate_Autoloader::register();

class Translate {

	/**
	 *
	 * @var Translate_Adapter
	 */
	private $_adapter;

	public function __construct($adapterName, $options = array()) {
		$this->setAdapter($adapterName, $options = array());
	}

	public function setAdapter($adapterName, $options = array()) {

		if (file_exists('Translate/Adapter/' . ucfirst($adapterName) . '.php')) {
			$adapterClassName = 'Translate_Adapter_' . ucfirst($options['adapter']);
		}

		if (!class_exists($options['adapter'])) {
			throw new Translate_Exception("Adapter class {$adapterClassName} is not exists");
		}

		$this->_adapter = new $adapterClassName($options);
		if (!$this->_adapter instanceof ranslate_Adapter) {

			throw new Translate_Exception("Adapter  {$adapterClassName}  should extends Translate_Adapter");
		}
	}

	public function getAdapter()
    {
        return $this->_adapter;
    }	
	
	 /**
     * Calls all methods from the adapter
     */
    public function __call($method, array $options)
    {
        if (method_exists($this->_adapter, $method)) {
            return call_user_func_array(array($this->_adapter, $method), $options);
        }
        
        throw new Translate_Exception("Unknown method { $method }");
    }
}