<?php

namespace Microkit\MicrokitPhpSdk;
require_once "./test/TestUtils.php"; // Adjust the path as necessary

use PHPUnit\Framework\TestCase;

final class MicrokitTest extends TestCase
{
    public function testMicrokit()
    {
        $service_name = 'permissions_test';
        $user = ['email' => 'someone@somewhere.com'];
        $token = '8da06796-8a54-418c-b7bf-f157de665359-wv0hb7nj+F/cFXHhrTFfJA==';
        $microkit = Microkit::initializeKit($token, $user, [
            'service' => $service_name,
            'baseUrl' => 'host.docker.internal',
            'http' => true,
            'port' => 8030,
            'pollingInterval' => 3,
            'pollingOn' => false,
        ]);
    
    $this->assertSame($microkit->configKit->postgres->dbname->getValue(), 'test_database');
   
    $this->assertSame($microkit->permissionsKit->permit('POST', '/articles/create', 'admin') , true);
    $this->assertSame($microkit->permissionsKit->permit('POST', '/articles/create', 'user') , false);
    $this->assertSame($microkit->featuresKit->first->getValue() , false);
    $this->assertSame($microkit->featuresKit->first->getValue(['email' => 'chaim@microkit.app']) , true);
    // TestUtils::updateFeature(true);
    // $microkit->update();
    // $microkit->featuresKit->first->change->subscribe(function ($current, $prev) use ($microkit) {
    //     print_r($current ? 'true' : 'false');
    //     // TestUtils::updateFeature(false);
    //     $microkit->close();
    //     // $this->assertSame($microkit->featuresKit->first->getValue() , true);
    // });
    $microkit->featuresKit->change->subscribe(function ($current, $prev) use ($microkit) {
        print_r($current);
        // TestUtils::updateFeature(false);
        $microkit->close();
        // $this->assertSame($microkit->featuresKit->first->getValue() , true);
    });
    
   
    // $microkit->update(false);
    // $this->assertSame($microkit->featuresKit->first->getValue() , true);
    }
}

// pecl install swoole
// docker-php-ext-enable swoole