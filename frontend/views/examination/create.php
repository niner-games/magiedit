<?php

use yii\helpers\Html;

/** @var array $users */
/** @var array $patients */
/** @var yii\web\View $this */
/** @var common\models\Examination $model */

$this->title = 'Create Examination';
$this->params['breadcrumbs'][] = ['label' => 'Examinations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="examination-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'Please fill out the following fields to add an examination.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'patients' => $patients,
    ]) ?>

</div>
