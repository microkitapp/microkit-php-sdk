<?php
namespace Microkit\MicrokitPhpSdk;

// require_once './ConfigItem.php'; // Adjust the path as necessary
// require_once './FeaturesItem.php'; // Adjust the path as necessary
// require_once './Publisher.php'; // Adjust the path as necessary

class Group {
    private $initialized = false;
    private $updated = false;
    private $group = [];
    private $_type;
    private $_types = ['config', 'features'];
    public $change;

    public function __construct($type, $values) {
        if (!$type) {
            throw new \Exception('Type is required!');
        }

        if (!$values) {
            throw new \Exception('Values are required!');
        }

        if (!in_array($type, $this->_types)) {
            throw new \Exception('Type is invalid!');
        }

        $this->_type = $type;
        $this->change = new Publisher();

        if ($values) {
            $this->update($values);
        }
    }

    public function update($values) {
        $prevValue = json_decode(json_encode($this->getValue()), true);
        foreach ($values as $key => $value) {
            if (isset($this->group[$key])) {
                $this->group[$key]->update($value);
            } else {
                if (!$this->initialized) {
                    $this->updated = true;
                }
                $this->group[$key] = $this->_setNewVal($value, $key);
                $this->group[$key]->change->subscribe(function ($current, $prev){
                    $this->updated = true;
                });
            }
        }
        $this->initialized = true;
        if ($this->updated) {
            $this->change->publish($this->getValue(), $prevValue);
            $this->updated = false;
        }
    }

    private function _setNewVal($val, $name) {
        if (is_array($val) && (!isset($val['type']) && !isset($val['value']))) {
            return new Group($this->_type, $val);
        } elseif ($this->_type === 'config') {
            return new ConfigItem($val, null);
        } elseif ($this->_type === 'features') {
            return new FeatureItem($val, $name);
        }
    }

    public function getValue() {
        $result = [];
        foreach ($this->group as $key => $item) {
            $result[$key] = $item->getValue();
        }
        return $result;
    }

    public function __get($name) {
        if (isset($this->group[$name])) {
            return $this->group[$name];
        }
        // Optionally handle the case where the property does not exist
    }

    // Implement __set if needed
}

?>
