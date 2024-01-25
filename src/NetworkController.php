<?php
namespace Microkit\MicrokitPhpSdk;
// require_once './HttpClient.php'; // Adjust the path as necessary
// require_once './Config.php'; // Adjust the path as necessary
// require_once './Publisher.php'; // Adjust the path as necessary

class NetworkController {

    private $client;
    private $_updateInterval;
    public $update;

    private static $instance = null;

    public function __construct() {
        $this->client = new HttpClient();
        $this->update = new Publisher();
        NetworkController::$instance = $this;
    }

    public function getLatestData() {
        return $this->client->get('init');
    }

    public function getUserData($data) {
        return $this->client->makeRequest('get_user_data', 'get_feature_by_user', $data);
    }

    public function permit ($data) {
        return $this->client->makeRequest('check_permissions', 'permit', $data);
    }

    public function notify ($data) {
        return $this->client->makeRequest('send_notifications', 'notify', $data);
    }

    public function update() {
       
        try {
            $res = $this->client->get('update');
            
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $res;
    }

    public function startUpdateInterval() {
        $config =  Config::getInstance();
        $interval = $config->get('pollingInterval') * 1000; // Convert to milliseconds
        $this->_updateInterval = \Swoole\Timer::tick($interval, function () {
            try {
                $res = $this->client->get('update');
                $this->update->publish($res, null);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        });
        return $this->update;
    }

    public function stopUpdateInterval() {
        if ($this->_updateInterval) {
            \Swoole\Timer::clear($this->_updateInterval);
        }
        $this->update->unsubscribeAll();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new NetworkController();
        }
        return self::$instance;
    }

}

?>
