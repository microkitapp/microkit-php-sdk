<?php
namespace Microkit\MicrokitPhpSdk;
// require_once "./Item.php"; // Adjust the path as necessary
// require_once "./NetworkController.php"; // Adjust the path as necessary

class FeatureItem extends Item {

    public function getValue($user = null) {
        // Implement async behavior if needed, PHP does not support native async/await.
        if (!$user) {
            return $this->getItemValue($this->_item);
        } else {
            $features = [
                $this->_name => [
                    "value" => $this->_item['value'], 
                    "type" => $this->_item['type'], 
                    "targeting_groups" => $this->_item['targeting_groups']
                ]
            ];
            $res = NetworkController::getInstance()->getUserData(['user' => $user, 'features' => $features]);
            return $this->getItemValue($res['features'][$this->_name]);
        }
    }

}

?>
