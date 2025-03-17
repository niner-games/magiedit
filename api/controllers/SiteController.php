<?php

namespace api\controllers;

use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * Seems that it must be empty to get at least somewhat close into getting "normal" response, i.e. 404 Not Found
     * instead of nasty and irritating 500 Internal Server Error.
     *
     * https://forum.yiiframework.com/t/how-to-correctly-handle-rest-api-errors/135615
     *
     * https://www.yiiframework.com/doc/guide/2.0/en/rest-error-handling
     * https://www.yiiframework.com/doc/guide/2.0/en/runtime-responses
     */
    public function actionError()
    {

    }
}
