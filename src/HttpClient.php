<?php
namespace Microkit\MicrokitPhpSdk;

class HttpClient {
    private $config;
    public function __construct() {
        $this->config = Config::getInstance();
        if (!$this->config->get('apiKey')) {
            throw new \Exception('apiKey is required');
        }
    }

    public function makeRequest($action, $path = '', $data = null, $method = 'POST') {

        $postData = json_encode(array_merge([
            'action' => $action,
            'lang' => 'PHP',
            'options' => [
                'service' => $this->config->get('service')
            ],
            'user' => $this->config->get('user')
        ], $data ?: [], $this->config->get('key_vars_values') ? ['key_vars_values' => $this->config->get('key_vars_values')] : []));

        $port = $this->config->get('port') ?: ($this->config->get('http') ? 80 : 443);
        $url = 'http' . ($this->config->get('http') ? '' : 's') . '://' . $this->config->get('baseUrl') . ($port ? ":$port" : "") . '/' . $this->config->get('version') . '/' . $path;

        $opts = [
            'http' => [
                'method' => $method,
                'header' => "Content-Type: application/json\r\n" .
                            "Authorization: Bearer " . $this->config->get('apiKey') . "\r\n" .
                            "Content-Length: " . strlen($postData) . "\r\n",
                'content' => $postData,
                'ignore_errors' => true
            ]
        ];
        
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return "Error: Unable to make request";
        }

        return json_decode($result, true);
    }

    public function get($action) {
        return $this->makeRequest($action, '', null, 'POST');
    }
}

?>
