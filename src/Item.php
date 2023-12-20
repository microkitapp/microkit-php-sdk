<?php

namespace Microkit\MicrokitPhpSdk;
// require_once "./Publisher.php";
class Item {

    protected $_item;
    protected $_name;
    public $change;

    public function __construct($item, $name) {
        $this->_name = $name;
        $this->change = new Publisher(); // Ensure you have a Publisher class implemented
        $this->setValue($item);
    }

    public function setValue($item) {
        $changed = false;
        if (is_object($item) && is_object($this->_item)) {
            $changed = $item->type != $this->_item->type || $item->value != $this->_item->value;
        } else {
            $changed = $item != $this->_item;
        }
        $prevItem = $this->_item;
        $this->_item = $this->cloneItem($item);

        if ($changed) {
           
            $this->change->publish($this->getItemValue($item), $this->getItemValue($prevItem));
        }
    }

    public function getValue($user = null) {
        return $this->getItemValue($this->_item);
    }

    public function update($value) {
        $this->setValue($value);
    }

    private function getItemValueFromObject($item) {
        switch ($item->type) {
            case 'string':
                return (string) $item->value;
            case 'json':
                return json_decode(str_replace('\'', '"', $item->value), true);
            case 'number':
                return intval($item->value);
            case 'boolean':
                return $item->value == 'true' || $item->value == true;
            default:
                return $item->value;
        }
    }

    private function getItemValueFromArray($item) {
        switch ($item['type']) {
            case 'string':
                return (string) $item['value'];
            case 'json':
                return json_decode(str_replace('\'', '"', $item['value']), true);
            case 'number':
                return intval($item['value']);
            case 'boolean':
                return $item['value'] == 'true' || $item['value'] == true;
            default:
                return $item['value'];
        }
    }

    protected function getItemValue($item) {
        return is_object($item) ? $this->getItemValueFromObject($item) : (is_array($item) ? $this->getItemValueFromArray($item) : $item);
    }

    private function cloneItem($item) {
        return is_object($item) ? json_decode(json_encode($item), true) : $item;
    }

}

?>
