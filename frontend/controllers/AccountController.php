<?php

namespace frontend\controllers;

use Yii;

use frontend\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;

use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;

use yii\web\Controller;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Account controller implements account-related operations like login, signup, password reset etc.
 */
class AccountController extends Controller
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
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Logs in a user.
     *
     * @return string|Response
     */
    public function actionLogin(): string|Response
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

        Yii::$app->session->setFlash('success', Yii::t('controllers', 'You’ve successfully logged out. For added security, consider closing all browser windows.'));

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
            Yii::$app->session->setFlash('success', Yii::t('controllers', 'Welcome aboard! We’ve sent a verification email to your inbox. If you don’t see it, be sure to check your spam or junk folder — just in case.'));

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
                Yii::$app->session->setFlash('success', Yii::t('controllers', 'Please click the link in your email to confirm your account.'));

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', Yii::t('controllers', 'Unfortunately, we can’t reset the password for this email address.'));
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
            Yii::$app->session->setFlash('success', Yii::t('controllers', 'Your password has been changed successfully.'));

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
            Yii::$app->session->setFlash('success', Yii::t('controllers', 'Your email address has been confirmed!'));

            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', Yii::t('controllers', 'Unfortunately, we can’t verify your account with the token provided. If the link has expired, try requesting a new one.'));

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
                Yii::$app->session->setFlash('success', Yii::t('controllers', 'Please click the link in your email to confirm your account.'));

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', Yii::t('controllers', 'Unfortunately, we can’t resend the verification email to this address.'));
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
