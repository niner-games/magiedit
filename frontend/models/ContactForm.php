<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    /**
     * PROPERTIES
     */
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * CONFIGURATION
     */

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['verifyCode', 'captcha'],
            [['name', 'email', 'subject', 'body'], 'required'],
            ['email', 'email', 'message' => Yii::t('frontend-models', 'This field must contain a valid e-mail address.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => Yii::t('frontend-models', 'Verification Code')
        ];
    }

    /**
     * REGULAR METHODS
     */

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
