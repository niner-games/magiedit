<?php

use yii\helpers\Html;

/** @var array $users */
/** @var array $examinations */
/** @var yii\web\View $this */
/** @var common\models\Result $model */

$this->title = 'Update Result: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>

<div class="result-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'Please use this form to update selected result.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'examinations' => $examinations,
    ]) ?>

</div>
