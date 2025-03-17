<?php

namespace frontend\controllers;

use common\models\Examination;
use common\models\Result;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ResultController implements the CRUD actions for Result model.
 */
class ResultController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists all Result models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Result::find(),
            'pagination' => ['pageSize' => 10],
            'sort' => ['defaultOrder' => ['examination_id' => SORT_ASC]],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Result model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Result model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Result();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $users = User::getAllUsersAsArray();

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
            'examinations' => $this->parseExaminationsArray(Examination::getAllExaminationsAsArray(), $users),
        ]);
    }

    /**
     * Updates an existing Result model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $users = User::getAllUsersAsArray();

        return $this->render('update', [
            'model' => $model,
            'users' => $users,
            'examinations' => $this->parseExaminationsArray(Examination::getAllExaminationsAsArray(), $users),
        ]);
    }

    /**
     * Deletes an existing Result model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Result model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Result the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Result::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend-controllers', 'The requested page does not exist.'));
    }

    /**
     * Replaces user ID with actual user name and user surname inside examinations array and formats date correctly
     *
     * @param array $examinations examinations array
     * @param array $users users array
     * @return array parsed array
     */
    protected function parseExaminationsArray(array $examinations, array $users): array
    {
        $resultingArray = [];

        foreach($examinations as $key => $examination) {
            list($timestamp, $user) = explode(' ', $examination);

            $user = substr($user, 1, -1);
            $examinations[$key] = Yii::$app->formatter->asDate($timestamp, 'd MMMM y, kk:mm:ss') . ' (' . $users[$user] . ')';
        }

        return $examinations;
    }
}
