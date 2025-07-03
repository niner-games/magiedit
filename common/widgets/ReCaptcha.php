<?php

namespace common\widgets;

use Yii;

/**
 * Overrides base class only to fix a PHP 8.2+ mismatch of using NULL as second argument for http_build_query() function.
 *
 * In PHP 7.1 (in which base extension is written) http_build_query() function allowed NULL (or any non-string) value for
 * $numeric_prefix argument. In PHP 8.2+ it triggers a warning and in PHP 9 it will cause a syntax error. Here we are duplicating
 * an exact implementation of the original getApiParams() method only forcing empty string as $numeric_prefix argument for
 * the http_build_query() function call.
 *
 * See https://www.php.net/manual/en/function.http-build-query.php for details.
 */
class ReCaptcha extends \recaptcha\ReCaptcha
{
    /**
     * {@inheritdoc}
     */
    public function getApiParams(): string
    {
        $result = [
            'hl' => Yii::$app->language,
            'render' => $this->render,
        ];

        if ($this->callback !== null) {
            $result['onload'] = $this->onloadCallbackName;
        }

        return http_build_query($result, '', "&", \PHP_QUERY_RFC3986);
    }
}