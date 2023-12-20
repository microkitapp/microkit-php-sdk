<?php
namespace Microkit\MicrokitPhpSdk;

class Permit {
    
    public function permit($method, $path, $role, $data = []) {
            $config = Config::getInstance();
            $project_id = $config->get('key_vars_values')['project_id'];
            $res = NetworkController::getInstance()->permit(['method' => $method, 'path' => $path, 'role' => $role, 'project_id' => $project_id, 'service' => $config->get('service') ?? '', 'data' => $data]);
            return $res['permit'] ?? false;
        }

}

?>