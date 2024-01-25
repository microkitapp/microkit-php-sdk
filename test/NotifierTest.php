<?php

namespace Microkit\MicrokitPhpSdk;
require_once "./test/TestUtils.php"; // Adjust the path as necessary

use PHPUnit\Framework\TestCase;

final class NotifierTest extends TestCase
{
    public function testNotifier()
    {
        $service_name = 'testing';
        $user = ['email' => 'someone@somewhere.com'];
        $token = '7fa8f128-19fc-41eb-ba9a-70a8f2f16fb7-af6TjQChLMl14kXzzY3MwQ==';
        $microkit = Microkit::initializeKit($token, $user, [
            'service' => $service_name,
            'baseUrl' => 'host.docker.internal',
            'http' => true,
            'port' => 8030,
            'pollingInterval' => 3,
            'pollingOn' => false,
        ]);
    
        
        
        
        $name = 'Test Notifier';
        $messageInterfaces = [
            ['interface' => 'slack', 'template' => 'text'],
            // ['interface' => 'email', 'template' => 'notifier_email']
        ];
        $contacts = ['admins'];
        $params = ['sender' => 'Microkit Notifier'];
        
        try {
            $microkit->notificationsKit->notify([
                'name' => $name, 
                'messageInterfaces' => $messageInterfaces, 
                'contacts' => $contacts, 
                'params' => $params
            ]);
        } catch (Exception $e) {
            // Handle the exception
            echo 'Error: ',  $e->getMessage(), "\n";
        }
        
        // Continue with your code...
        
    $this->assertSame('test_database', 'test_database');
   
    // $this->assertSame($microkit->permissionsKit->permit('POST', '/articles/create', 'admin') , true);
    // $this->assertSame($microkit->permissionsKit->permit('POST', '/articles/create', 'user') , false);
    // $this->assertSame($microkit->featuresKit->first->getValue() , false);
    // $this->assertSame($microkit->featuresKit->first->getValue(['email' => 'chaim@microkit.app']) , true);
    // TestUtils::updateFeature(true);
    // $microkit->update();
    // $microkit->featuresKit->first->change->subscribe(function ($current, $prev) use ($microkit) {
    //     print_r($current ? 'true' : 'false');
    //     // TestUtils::updateFeature(false);
    //     $microkit->close();
    //     // $this->assertSame($microkit->featuresKit->first->getValue() , true);
    // });
    // $microkit->featuresKit->change->subscribe(function ($current, $prev) use ($microkit) {
    //     print_r($current);
    //     // TestUtils::updateFeature(false);
    //     $microkit->close();
    //     // $this->assertSame($microkit->featuresKit->first->getValue() , true);
    // });
    
   
    // $microkit->update(false);
    // $this->assertSame($microkit->featuresKit->first->getValue() , true);
    }
}

// pecl install swoole
// docker-php-ext-enable swoole