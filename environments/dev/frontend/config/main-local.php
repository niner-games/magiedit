<?php

$config = [
    'components' => [
        'request' => [
            'cookieValidationKey' => 'Zrnn7XKzRzvq51gc0VBUuyOT4MslAewu7fUrnZyUF2zWuSdJtlrt4Ta5a9JRaBrO'
        ],

        /**
         * For DEV and TEST environments you can use test pair of keys, however generating a regular pair of keys (different
         * from for PROD environment!) is recommended. Keep in mind that test keys will always return 1 meaning that when
         * using them you will always pass an actual reCAPTCHA verification (which may be unwanted).
         *
         * https://developers.google.com/recaptcha/docs/faq#id-like-to-run-automated-tests-with-recaptcha.-what-should-i-do
         *
         * Since this is a non-PROD environment, these test keys has been used as default. Generate your own (see README.md),
         * if you wish to use them here.
         *
         * https://www.google.com/u/1/recaptcha/admin/create
         * https://developers.google.com/recaptcha/docs/faq#id-like-to-run-automated-tests-with-recaptcha.-what-should-i-do
         *
         * If you are generating a "real" pair for test purposes, include "localhost" and "127.0.0.1" domains in key settings.
         */
        'recaptcha' => [
            'class' => 'recaptcha\ReCaptchaComponent',
            'siteKey' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
            'secretKey' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
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
