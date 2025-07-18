<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'tpw-frontend',
    'basePath' => dirname(__DIR__),
    'name' => 'MagiEdit',
    'language' => 'en',
    'bootstrap' => [
        'log',
        [
            'class' => 'common\components\LanguageSelector',
            'cookieName' => 'language-frontend',
            'supportedLanguages' => ['en', 'pl'],
        ]
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class
            ]
        ],
        'user' => [
            'class' => yii\web\User::class,
            'enableAutoLogin' => true,
            'identityClass' => 'common\models\User',
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'tpw-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/../translations',
                ],
            ],
        ],
    ],
    'params' => $params,
];
