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
 * Details: https://github.com/akademia-slaska/template-repository#releases
 * More details: https://git-scm.com/docs/git-tag
 */
class ApplicationVersion
{
    const MAJOR = 0;
    const MINOR = 5;
    const PATCH = 0;
    const GITHUB_REPOSITORY = 'https://github.com/akademia-slaska/tcmed-platform-web/';

    public static function get($useGit = true, $addEnvironment = true, $formatOutput = true): string
    {
        $commitHash = ($useGit) ? trim(exec('git log --pretty="%h" -n1 HEAD')) : '';
        $releaseVersion =  ($useGit) ? trim(exec('git describe --tags --abbrev=0')) : '';
        $releaseVersion = ($releaseVersion === '') ? self::MAJOR.'.'.self::MINOR.'.'.self::PATCH : $releaseVersion;

        if ($formatOutput)
        {
            $cssStyle = [
                'style' => Html::cssStyleFromArray([
                    'color' => 'inherit',
                    'font-weight' => '500',
                    'text-decoration' => 'none'
                ])
            ];

            if (YII_DEBUG) {
                $commitHash = ($commitHash !== '') ? Html::a($commitHash, self::GITHUB_REPOSITORY.'commit/'.$commitHash, $cssStyle) : '';
                $releaseVersion = ($useGit) ? Html::a($releaseVersion, self::GITHUB_REPOSITORY.'releases/tag/'.$releaseVersion, $cssStyle) : Html::tag('span', $releaseVersion, $cssStyle);
            } else {
                $commitHash = ($commitHash !== '') ? Html::tag('span', $commitHash, $cssStyle) : '';
                $releaseVersion = Html::tag('span', $releaseVersion, $cssStyle);
            }
        }


        $result = $releaseVersion;
        $result .= ($commitHash !== '') ? '.'.$commitHash : '';

        if ($addEnvironment)
        {
            $environmentName = getenv('ENVIRONMENT_NAME', true) ?: getenv('ENVIRONMENT_NAME');
            if ($environmentName !== '')  $result .= ($formatOutput) ? '.'.Html::tag('span', $environmentName, $cssStyle) : $environmentName;
        }


        return $result;
    }
}