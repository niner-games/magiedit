<?php

namespace frontend\controllers;

use Yii;

use common\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\ResetPasswordForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;

use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;

use yii\web\Controller;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return Response the current response object
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        Yii::$app->session->setFlash('success', Yii::t('frontend-controllers', 'You signed out of your account. It\'s a good idea to close all browser windows.'));

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return string|Response the current response object or rendered view
     */
    public function actionSignup(): Response|string
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', Yii::t('frontend-controllers', 'Thank you for registration. Please check your inbox for verification email.'));

            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return string|Response the current response object or rendered view
     */
    public function actionRequestPasswordReset(): Response|string
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('frontend-controllers', 'Check your email for further instructions.'));

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', Yii::t('frontend-controllers', 'Sorry, we are unable to reset password for the provided email address.'));
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password using provided password reset token.
     *
     * @param string $token reset password token
     *
     * @return string|Response the current response object or rendered view
     * @throws BadRequestHttpException if provided token is invalid
     */
    public function actionResetPassword(string $token): Response|string
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('frontend-controllers', 'New password saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address using provided email verification token.
     *
     * @param string $token email verification token
     *
     * @return Response the current response object
     * @throws BadRequestHttpException f provided token is invalid
     */
    public function actionVerifyEmail(string $token): Response
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', Yii::t('frontend-controllers', 'Your email has been confirmed!'));

            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', Yii::t('frontend-controllers', 'Sorry, we are unable to verify your account with provided token.'));

        return $this->goHome();
    }

    /**
     * Resend verification email.
     *
     * @return string|Response the current response object or rendered view
     */
    public function actionResendVerificationEmail(): Response|string
    {
        $model = new ResendVerificationEmailForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('frontend-controllers', 'Check your email for further instructions.'));

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', Yii::t('frontend-controllers', 'Sorry, we are unable to resend verification email for the provided email address.'));
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
