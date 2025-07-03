<?php

return [
    'components' => [
        'request' => [
            'baseUrl' => '',
            'cookieValidationKey' => 'kWjXHeImhfYTe6Z8cJCdryxSzIOYwcKyWfosWSc3QHnQ9YZFMtAJ4buQWklx8TBw'
        ],

        /**
         * For PROD environment you must always generate your own pair of keys that is different from pair used in DEV or
         * TEST environments (see README.md).
         *
         * https://www.google.com/u/1/recaptcha/admin/create
         * https://developers.google.com/recaptcha/docs/faq#id-like-to-run-automated-tests-with-recaptcha.-what-should-i-do
         *
         * If you are generating a pair for production purposes, include actual domain (i.e. "magiedit.com") in key settings.
         */

        'recaptcha' => [
            'class' => 'recaptcha\ReCaptchaComponent',
            'siteKey' => 'your-prod-site-key',
            'secretKey' => 'your-prod-secret-key',
        ],
        'urlManager' => [
            'baseUrl' => '',
        ],
    ],
];
