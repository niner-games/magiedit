<?php

use yii\helpers\Html;

/** @var array $users */
/** @var array $patients */
/** @var yii\web\View $this */
/** @var common\models\Examination $model */

$this->title = 'Update Examination: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Examinations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>

<div class="examination-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'Please use this form to update selected examination.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'patients' => $patients,
    ]) ?>

</div>
