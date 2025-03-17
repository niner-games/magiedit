<?php

/**
 * Author: Tomasz Trejderowski
 * Created on: Oct 27, 2023
 *
 * Extends \yii\web\User to provide additional, locally needed getIsAdmin() method.
 */

namespace common\components;

use Yii;

class User extends \yii\web\User
{
    public string $cookieName = 'language';
    public array $supportedLanguages = [];

    /**
     * Returns a value indicating whether the user is authenticated and has user type of administrator.
     * @return bool whether the current user is an administrator
     */
    public function getIsAdmin(): bool
    {
        $identity = $this->getIdentity();

        return $identity !== null && $identity->type === \common\models\User::TYPE_ADMINISTRATOR;
    }
}