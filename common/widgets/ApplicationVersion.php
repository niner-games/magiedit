<?php

namespace common\widgets;

use yii\helpers\Html;

/**
 * Code based on: https://stackoverflow.com/a/33986403/1469208
 *
 * To upgrade version:
 *
 * 1. Change MAJOR, MINOR and/or PATCH constants here
 * 2. Commit change (along with actually changed code of course)
 * 3. Execute: git tag -a 0.0.1 -m "Short version description"
 * 4. Execute: git push --tags
 *
 * Details: https://github.com/niner-games/template-repository#releases
 * More details: https://git-scm.com/docs/git-tag
 */
class ApplicationVersion
{
    const MAJOR = 0;
    const MINOR = 1;
    const PATCH = 0;

    public static function get($useGit = true, $addEnvironment = true, $formatOutput = true): string
    {
        if ($formatOutput)
        {
            $cssStyle = [
                'style' => Html::cssStyleFromArray([
                    'color' => 'inherit',
                    'font-weight' => '500',
                    'text-decoration' => 'none'
                ])
            ];
        }

        $result = self::MAJOR.'.'.self::MINOR.'.'.self::PATCH;

        if ($addEnvironment)
        {
            $environmentName = getenv('ENVIRONMENT_NAME', true) ?: getenv('ENVIRONMENT_NAME');
            if ($environmentName !== '')  $result .= ($formatOutput) ? '.'.Html::tag('span', $environmentName, $cssStyle) : $environmentName;
        }

        return $result;
    }
}