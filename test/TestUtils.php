<?php

namespace Microkit\MicrokitPhpSdk;

class TestUtils {
    public static function updateFeature ($value = true) {
            $url = 'http://host.docker.internal/api/features/b0067016-184e-494c-8be9-d6f56b48b4cb/features/first';
            $method = 'PUT';

            $headers = array(
                'Accept: application/json, text/plain, */*',
                'Accept-Language: en-US,en;q=0.9',
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImU2NmIyOGU2LWQzMWUtNDI0Ni1hYzcyLTk1MDA1MTQzOWM5ZiIsIm5hbWUiOiLXl9eZ15nXnSDXldeZ15PXkdeo15IiLCJlbWFpbCI6ImNoYWltdmFpZEBnbWFpbC5jb20iLCJ0eXBlIjoxLCJpYXQiOjE3MDMwMDUyNTYsImV4cCI6MTcwMzA5MTY1Nn0.2ycoKsHvORktHu2VwUyEgV4KQOEf3VrooVHRrFMB_fQ',
                'Connection: keep-alive',
                'Content-Type: application/json',
                'Origin: http://localhost',
                'Referer: http://localhost/features',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
                'sec-ch-ua: "Google Chrome";v="119", "Chromium";v="119", "Not?A_Brand";v="24"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "macOS"'
            );

            $data = array(
                'name' => 'first',
                'key' => 'first',
                'type' => 'boolean',
                'services' => [],
                'description' => '',
                'randomActive' => false,
                'variations' => array(
                    '0' => array('label' => 'OFF', 'value' => false, 'percent' => 0),
                    '1' => array('label' => 'ON', 'value' => true, 'percent' => 0)
                ),
                'values' => array(
                    array('env' => '4da0c919-7dfc-4c14-a065-a83c34fdede7', 'variation' => $value ? '1' : '0'),
                    array('env' => 'ccd762bf-bb54-47a5-901a-a9cda4d239b4', 'variation' => '0'),
                    array('env' => 'd4b1cb4a-0936-4003-8f16-ddbbd9528666', 'variation' => '0')
                ),
                'created_at' => '2023-12-08T09:18:24.288Z',
                'targeting_groups' => array(
                    array('id' => 'dd24f9c6-0738-499b-9f60-1314164d8244', 'variation' => '1', 'active' => true, 'values' => [])
                )
            );

            $data_json = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'cURL error: ' . curl_error($ch);
            }

            curl_close($ch);

            // echo $response;
            

    }
}