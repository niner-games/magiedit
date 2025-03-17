<?php

putenv('ENVIRONMENT_NAME=prod');

$dbScheme = 'mysql';
$dbHost = 'localhost';
$dbName = 'tcmed-platform-web';
$dbUsername = 'scripts';
$dbPassword = 'P#gtitO@D:=~5C.7?IDyF1?dZ2Yo|*';
$dbCharset = 'utf8';

$mailScheme = 'smtp';
$mailHost = 'poczta.o2.pl';
$mailUsername = 'username@o2.pl';
$mailPassword = 'strong123P@SSWORD!';
$mailPort = '465';

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => $dbScheme.':'.'host'.'='.urlencode($dbHost).';'.'dbname='.$dbName,
            'username' => $dbUsername,
            'password' => $dbPassword,
            'charset' => $dbCharset,
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'scheme' => $mailScheme,
                'host' => $mailHost,
                'username' => $mailUsername,
                'password' => $mailPassword,
                'port' => $mailPort,
                'dsn' => $mailScheme.'://'.urlencode($mailUsername).':'.urlencode($mailPassword).'@'.urlencode($mailHost).':'.$mailPort,
            ],
        ],
    ],
];
