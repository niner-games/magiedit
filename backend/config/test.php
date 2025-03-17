<?php
return [
    'id' => 'tpw-backend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            /*
             * https://randomkeygen.com/
             */
            'cookieValidationKey' => 's9F4Ksf55jfLusspL6kn4S3YeqhqN71bm8OwjXQTQqM9J3PuIpLY4tHYPgFT2RHD'
        ],
    ],
];
