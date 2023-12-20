<?php
namespace Microkit\MicrokitPhpSdk;
class Publisher {
    private $lastValue;
    private $subscribers = [];

    public function subscribe($callback) {
        $key = $this->generateKey();
        $this->subscribers[$key] = $callback;
        return [
            'unsubscribe' => function() use ($key) { unset($this->subscribers[$key]); },
            'call' => function() use ($callback, $key) {
                $callback($this->lastValue);
                return [
                    'unsubscribe' => function() use ($key) { unset($this->subscribers[$key]); }
                ];
            }
        ];
    }

    public function publish($currentValue, $prevValue) {
        $this->lastValue = $currentValue;
        foreach ($this->subscribers as $key => $subscriber) {
            $subscriber($currentValue, $prevValue);
        }
    }

    private function generateKey() {
        $result = '';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < 10; $i++) {
            $result .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $result;
    }

    public function unsubscribeAll() {
        $this->subscribers = [];
    }
}

?>
