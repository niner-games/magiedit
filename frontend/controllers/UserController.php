<?php

namespace frontend\controllers;

use common\models\User;

use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
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
    public function actionIndex(): string
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
     * @throws NotFoundHttpException|InvalidConfigException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        Yii::$app->getUser()->setReturnUrl($this->request->getUrl());

        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new User model. If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|Response
     * @throws Exception in case update or insert failed.
     */
    public function actionCreate(): Response|string
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
            'model' => $model
        ]);
    }

    /**
     * Updates an existing User model. If update is successful, redirects to either 'view' or 'index' page, depending on
     * which page has actually performer model update (@see Yii::$app->user->getReturnUrl()).
     *
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);

        if ($this->request->isPost)
        {
            $wasAdmin = Yii::$app->user->identity->getIsAdmin();

            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->user->identity = User::findOne(Yii::$app->user->id);

                if ($model->getIsCurrentUser() && $wasAdmin && !Yii::$app->user->identity->getIsAdmin()) {
                    return $this->goHome();
                }

                return $this->redirect(Yii::$app->user->getReturnUrl());
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return Response
     * @throws Throwable in case delete failed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException if optimistic locking is enabled and the data being deleted is outdated
 */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->id === $model->id) {
            Yii::$app->session->setFlash('error', Yii::t('controllers', 'You cannot delete currently logged-in user.'));

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

        throw new NotFoundHttpException(Yii::t('controllers', 'The requested page does not exist.'));
    }
}
