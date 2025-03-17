<?php

$config = [
    'components' => [
        'request' => [
            /*
             * https://randomkeygen.com/
             */
            'cookieValidationKey' => 'OtstH6ekHOErxBBuRfaZyPFyFdddefxjteQj3vW4rMZCfyAzHZb9FsfS5o6lS1dd'
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
    ];
}

return $config;
