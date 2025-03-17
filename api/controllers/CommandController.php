<?php

namespace api\controllers;

use common\models\Command;
use common\models\Device;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\rest\ActiveController;
use yii\web\ConflictHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class CommandController extends ActiveController
{
    public $modelClass = Command::class;

    /**
     * Force API application to use actionUpdate() implemented in this controller, not the default one.
     *
     * @see yii\rest\UpdateAction::run()
     * @see yii\rest\ActiveController::actions()
     */
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['update']);

        return $actions;
    }

    /**
     * Updates an existing Command model. Removes '+' and ' ' characters from 'result' field. This characters are returned
     *  by the device, but are unnecessary in actual database.
     *
     * @param string $id the primary key of the model.
     * @return Command the model being updated
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException if there is any error when updating the model
     * @throws InvalidConfigException
     */
    public function actionUpdate(string $id): Command
    {
        $model = $this->findModel($id);

        $model->scenario = Model::SCENARIO_DEFAULT;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->result = str_replace([' ','+'], "", $model->result);

        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }

    /**
     * Finds latest Command model sent to the device identified by provided ServerID (id).
     *
     * If the model is not found, a 404 HTTP Not Found exception will be thrown. A 409 HTTP Conflict exception will be thrown,
     * if there is no device found under provided serverID (id). Finally, a 405 HTTP Method Not Allowed exception wii be
     * thrown, if the found device is inactive.
     *
     * @param integer $id ServerID (id)
     *
     * @return array|ActiveRecord Command model that was found or empty array
     * @throws NotFoundHttpException if there is no command found for given device
     * @throws ConflictHttpException if the device cannot be found
     * @throws MethodNotAllowedHttpException if the device is found, but is inactive
     */
    public function actionFind(int $id): array|ActiveRecord
    {
        if (($device = Device::find()->where(['id' => $id])->one()) !== null) {
            if ($device->status === Device::STATUS_ACTIVE) {
                if (($command = Command::find()->where(['to' => $id, 'status' => Command::STATUS_SENT])->orderBy('updated_at DESC')->one()) !== null) {
                    return $command;
                }

                throw new NotFoundHttpException('The requested command cannot be found.');
            }

            throw new MethodNotAllowedHttpException('The requested device is inactive.');
        }

        throw new ConflictHttpException('The requested device cannot be found.');
    }

    /**
     * Finds the Command model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Command the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Command
    {
        if (($model = Command::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested command cannot be found.');
    }
}