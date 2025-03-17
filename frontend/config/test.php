<?php
return [
    'id' => 'app-frontend-tests',
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
            'cookieValidationKey' => 'q4u38cHoesyfQqLx8bPrynDePovc8SygBWWnUTnrdDczOjMiJ8TOLi181rh8NJ4C'
        ],
        'mailer' => [
            'messageClass' => \yii\symfonymailer\Message::class
        ]
    ],
];
