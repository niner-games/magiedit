<?php

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/main.php',
    require __DIR__ . '/main-local.php',
    require __DIR__ . '/test.php',
    require __DIR__ . '/test-local.php',
    [
        'components' => [
            'request' => [
                /*
                 * https://randomkeygen.com/
                 */
                'cookieValidationKey' => 'oDNxpCt2tP0XiGU5XZuepFNGygKq4x7WYMbnkD7ZmUKNMpzQTBXupkJJ3SxiNw3A'
            ],
        ],
    ]
);
