<?php

namespace api\controllers;

use common\models\Device;
use yii\rest\ActiveController;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

class DeviceController extends ActiveController
{
    public $modelClass = Device::class;

    /**
     * Finds the Device model based on provided ThingID (tid).
     *
     * If the model is not found, a 404 HTTP Not Found exception will be thrown. A 405 HTTP Method Not Allowed exception
     * exception will be thrown, if the device under provided serverID (id) is inactive.
     *
     * @param string $tid ThingID (tid)
     *
     * @return array|\yii\db\ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws MethodNotAllowedHttpException if the model refers an inactive device
     */
    public function actionFind(string $tid): array|\yii\db\ActiveRecord
    {
        if (($device = Device::find()->where(['tid' => $tid])->one()) !== null) {
            if ($device->status === Device::STATUS_ACTIVE) {
                return $device;
            }

            throw new MethodNotAllowedHttpException('The requested device is inactive.');
        }

        throw new NotFoundHttpException('The requested device cannot be found.');
    }
}