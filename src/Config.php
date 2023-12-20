<?php
namespace Microkit\MicrokitPhpSdk;

class Config {
    private $_config = [
        'version' => 'v1'
    ];
    private static $instance = null;

    public static function init($config) {
        if (self::$instance == null) {
            self::$instance = new Config();
        }
       self::$instance->_config['baseUrl'] = isset($config['baseUrl']) ? $config['baseUrl'] : (isset($config['base_url']) ? $config['base_url'] : 'sdk.microkit.app');
       self::$instance->_config['apiKey'] = $config['apiKey'];
       self::$instance->_config['port'] = isset($config['port']) ? $config['port'] : 443;
       self::$instance->_config['http'] = isset($config['http']) ? $config['http'] : false;
       self::$instance->_config['pollingInterval'] = isset($config['pollingInterval']) ? $config['pollingInterval'] : 30000;
       self::$instance->_config['pollingOn'] = isset($config['pollingOn']) ? $config['pollingOn'] : false;
       self::$instance->_config['service'] = $config['service'];
       self::$instance->_config['user'] = isset($config['user']) ? $config['user'] : [];

       self::$instance->_validate();

        return self::$instance;
    }

    private function _validate() {
        $this->_required('apiKey');
    }

    private function _required($field) {
        if(!isset($this->_config[$field])) {
            throw new \Exception($field . ' is required');
        }
    }

    public function get($configName) {
        return isset($this->_config[$configName]) ? $this->_config[$configName] : null;
    }

    public function set($configName, $value) {
        $this->_config[$configName] = $value;
    }

    public static function getInstance () {
        return self::$instance;
    }
}

// Usage
// $config = new Config();
// $config->init([...]);

?>
