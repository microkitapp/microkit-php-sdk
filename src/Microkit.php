<?php
namespace Microkit\MicrokitPhpSdk;
// require_once './Config.php'; // Adjust the path as necessary
// require_once './Group.php'; // Adjust the path as necessary
// require_once './NetworkController.php'; // Adjust the path as necessary
// require_once './Utils.php'; // Adjust the path as necessary

class MicroKit {

    private $microKit;
    private $_ready;
    private $_networkController;
    private $_readyResolve;
    private $_readyReject;
    public $configKit;
    public $featuresKit;
    public $permissionsKit;
    private $encryptionKey;

    public function __construct() {
        
    }

    public static function initializeKit($key, $user = [], $options = []) {
        $microKit = new self();
        $keys = Utils::extractKeys($key);
        $apiKey = $keys['apiKey'];
        $encryptionKey = $keys['encryptionKey'];

        $microKit->config = Config::init(array_merge($options, [
            'apiKey' => $apiKey,
            'user' => $user
        ]));

        $microKit->_networkController = new NetworkController();
        
        $res = $microKit->_networkController->getLatestData();
        $conf = isset($res['config']) ? Utils::decryptAecCbc($encryptionKey, $res['config']) : [];
        $microKit->config->set('key_vars_values', $res['key_vars_values'] ?? []);
        
        $microKit->encryptionKey = $encryptionKey;
        $microKit->configKit = new Group('config', $conf);
        $microKit->featuresKit = new Group('features', $res['features'] ?? []);
        $microKit->permissionsKit = new Permit();
        $microKit->notificationsKit = new Notifications();
           

        if ($microKit->config->get('pollingOn')) {
            $microKit->_networkController->startUpdateInterval()->subscribe(function ($current, $prev) use ($encryptionKey, &$microKit) {
                $conf = isset($current['config']) ? Utils::decryptAecCbc($encryptionKey, $current['config']) : [];
                $microKit->configKit->update($conf);
                $microKit->featuresKit->update($current['features'] ?? []);
            });
        }
        

    

        return $microKit;
    }

    public function update() { 
        $res = $this->_networkController->update();
        $conf = isset($res['config']) ? Utils::decryptAecCbc($this->encryptionKey, $res['config']) : [];
        $this->configKit->update($conf);
        $this->featuresKit->update($res['features'] ?? []);
    }

    public function kitReady() {
        return $this->microKit;
    }

    public function feature() {
        // Implement feature logic
    }

    public function close() {
        $this->_networkController->stopUpdateInterval();
        die();
    }
}

// Usage
// $microKit = new MicroKit();

?>
