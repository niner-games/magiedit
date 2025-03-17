<?php

$config = [
    'components' => [
        'request' => [
            /*
             * https://randomkeygen.com/
             */
            'cookieValidationKey' => 'FEWH98mLHmSDA4gXS0USL7LLIpb3DJpOvCO7u58e9IyFRXoEpOI5kNEio7J14gxH'
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
