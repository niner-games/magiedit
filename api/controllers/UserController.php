<?php

namespace api\controllers;

use common\models\User;

class UserController extends \yii\rest\ActiveController
{
    public $modelClass = User::class;
}