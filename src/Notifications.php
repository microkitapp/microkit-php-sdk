<?php

namespace Microkit\MicrokitPhpSdk;

class Notifications {
    public function notify($params) {
        $name = $params['name'];
        $messageInterfaces = $params['messageInterfaces'];
        $contacts = $params['contacts'];
        $additionalParams = $params['params'] ?? [];

        if (count($messageInterfaces) === 0 || !is_array($messageInterfaces)) {
            throw new Exception('messageInterfaces must be an array with at least one interface');
        }
        if (count($contacts) === 0 || !is_array($contacts)) {
            throw new Exception('contacts must be an array with at least one contact');
        }
        foreach ($messageInterfaces as $messageInterface) {
            if (!isset($messageInterface['interface']) || !isset($messageInterface['template'])) {
                throw new Exception('messageInterfaces must be an array of objects with interface and template keys');
            }
        }
        if (!is_array($additionalParams)) {
            throw new Exception('params must be an object');
        }

        if (empty($name)) {
            throw new Exception('Name is required, The name is used to identify the notification in the logs');
        }

        $config = Config::getInstance();
        $keyVarsValues = $config->get('key_vars_values');
        $projectId = $keyVarsValues['project_id'];
        $environmentId = $keyVarsValues['environment_id'];

        $networkController = NetworkController::getInstance();
        $networkController->notify([
            'name' => $name, 
            'message_interfaces' => $messageInterfaces, 
            'contacts' => $contacts, 
            'params' => $additionalParams, 
            'project_id' => $projectId, 
            'environment_id' => $environmentId
        ]);
    }
}
