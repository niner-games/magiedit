<?php

$config = [
    'components' => [
        'request' => [
            'cookieValidationKey' => 'Zrnn7XKzRzvq51gc0VBUuyOT4MslAewu7fUrnZyUF2zWuSdJtlrt4Ta5a9JRaBrO'
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
