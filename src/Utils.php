<?php
namespace Microkit\MicrokitPhpSdk;
class Utils {
    public static function decryptAecCbc($rawKey, $content, $outputEncoding = "utf8") {
        $key = base64_decode($rawKey);
        $content = base64_decode($content);
        $iv = substr($content, 0, 16);
        $cipherText = substr($content, 16);
        $decipher = openssl_decrypt($cipherText, "aes-128-cbc", $key, OPENSSL_RAW_DATA, $iv);
        return json_decode(trim($decipher), true);
    }
    
    public static function extractKeys($key) {
        $n = 24;
        return [
            'apiKey' => substr($key, 0, -$n - 1),
            'encryptionKey' => substr($key, -$n)
        ];
    }
    
    public static function generateApiKey($key, $service) {
        return $service ? $key . '-' . $service : $key;
    }
}


?>
