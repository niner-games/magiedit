<?php

namespace api\controllers;

use common\models\Examination;
use yii\rest\ActiveController;

class ExaminationController extends ActiveController
{
    public $modelClass = Examination::class;
}