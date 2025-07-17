<?php

/**
 * Author: Programmer Thailand
 * Created on: Jan 21, 2016
 * Website: https://www.yiiframework.com/wiki/835/language-selector
 *
 * Simple language selector. Change language by adding &language=en, &language=pl etc. to any URL or request.
 * See above website for more details. This is a modified version of the original code available above.
 */

namespace common\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Cookie;

class LanguageSelector implements BootstrapInterface
{
    public string $cookieName = 'language';
    public array $supportedLanguages = [];

    public function bootstrap($app): void
    {
        $newLanguage = $app->request->get('language');
        $preferredLanguage = isset($app->request->cookies[$this->cookieName]) ? (string) $app->request->cookies[$this->cookieName] : null;

        if(empty($preferredLanguage)) $preferredLanguage = $app->request->getPreferredLanguage($this->supportedLanguages);

        $app->language = $preferredLanguage;

        if($newLanguage !== null)
        {
            if (!in_array($newLanguage, $this->supportedLanguages)) {
                Yii::$app->session->setFlash('error', Yii::t('widgets', 'Unable to change application language. Unsupported language code.'));
            }

            $app->response->cookies->add(new Cookie([
                'name' => $this->cookieName,
                'value' => $newLanguage,
                'expire' => time() + 60 * 60 * 24 * 30, // 30 days
            ]));

            $app->language = $newLanguage;
        }
    }
}