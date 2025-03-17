<?php

namespace api\controllers;

use common\models\Patient;
use yii\rest\ActiveController;

class PatientController extends ActiveController
{
    public $modelClass = Patient::class;
}