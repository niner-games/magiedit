<?php

namespace frontend\controllers;

use common\models\User;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->user->identity && Yii::$app->user->identity->getIsAdmin();
                            },
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->getUser()->setReturnUrl($this->request->getUrl());

        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => ['pageSize' => 10],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        Yii::$app->getUser()->setReturnUrl($this->request->getUrl());

        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'isCurrentUser' => (Yii::$app->user->id === $model->id)
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->auth_key = '';
                $model->password_hash = '';

                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'isCurrentUser' => false
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->user->getReturnUrl());
        }

        return $this->render('update', [
            'model' => $model,
            'isCurrentUser' => (Yii::$app->user->id === $model->id)
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return Response
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->id === $model->id) {
            Yii::$app->session->setFlash('error', Yii::t('frontend-controllers', 'You cannot delete currently logged-in user.'));

            return $this->goBack();
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): User
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend-controllers', 'The requested page does not exist.'));
    }
}
