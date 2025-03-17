<?php

use yii\helpers\Html;

/** @var array $users */
/** @var yii\web\View $this */
/** @var common\models\Patient $model */

$this->title = 'Create Patient';
$this->params['breadcrumbs'][] = ['label' => 'Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="patient-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'Please fill out the following fields to add a patient.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
    ]) ?>

</div>
