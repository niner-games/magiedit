<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'tpw-api',
    'basePath' => dirname(__DIR__),
    'name' => 'Platforma rehabilitacyjna TC-MED sp. z o.o.',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class
            ]
        ],
        'user' => [
            'class' => \common\components\User::class,
            'enableAutoLogin' => true,
            'identityClass' => 'common\models\User',
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'tpw-api',
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
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => ['patient', 'result', 'user', 'examination'],
                    'pluralize' => false,
                ],
                /** For {tid} action's parameter "\S+" denotes "one or more non-whitespace characters" (https://www.phpliveregex.com/).
                 * https://forum.yiiframework.com/t/can-someone-help-me-understanding-regular-expressions-that-yii-2-uses/135665/4
                 */
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => ['device'],
                    'pluralize' => false,
                    'extraPatterns' => ['GET,HEAD find/{tid}' => 'find'],
                    'tokens' => [
                        '{id}' => '<id:\d+>',
                        '{tid}' => '<tid:\S+>',
                    ],
                ],
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => ['command'],
                    'pluralize' => false,
                    'extraPatterns' => ['GET,HEAD find/{id}' => 'find'],
                ],
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
