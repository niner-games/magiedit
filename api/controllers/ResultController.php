<?php

namespace api\controllers;

use common\models\Result;

class ResultController extends \yii\rest\ActiveController
{
    public $modelClass = Result::class;
}