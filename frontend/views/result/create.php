<?php

use yii\helpers\Html;

/** @var array $users */
/** @var array $examinations */
/** @var yii\web\View $this */
/** @var common\models\Result $model */

$this->title = 'Create Result';
$this->params['breadcrumbs'][] = ['label' => 'Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="result-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend-views', 'Please fill out the following fields to add a result.') ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'examinations' => $examinations,
    ]) ?>

</div>
