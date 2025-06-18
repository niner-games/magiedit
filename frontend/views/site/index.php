<?php

/** @var yii\web\View $this */
/** @var yii\db\ActiveQuery $results */
/** @var yii\db\ActiveQuery $patients */
/** @var yii\db\ActiveQuery $examinations */

use yii\bootstrap5\Html;

$this->registerJs("$('span').tooltip(); $('a').tooltip()");

?>

<div class="site-index" xmlns="http://www.w3.org/1999/html">

    <div class="p-4 mb-4 bg-transparent rounded-3">

        <div class="container-fluid py-5 text-center">

            <?= Html::img('@web/images/logo-oneliner-black.png', [
                'alt' => 'Logo of MagiEdit, one-liner version, black color',
                'width' => 461,
                'height' => 177,
            ]) ?>

            <p class="fs-5">

                <?= Yii::t('frontend-views', 'A web application for creating paragraph games and visual novels') ?>

            </p>

    </div>

    <div class="body-content">

        <div class="row">

            <div class="col-lg-4">

                <h2><?= Yii::t('frontend-views', 'Patients') ?></h2>

                <p><?= Yii::t('frontend-views', 'Recently added patients') ?>:</p>

                <table class="homepage-table">

                    <?php for ($cnt = 0; $cnt < 5; $cnt++): ?>

                        <tr>

                            <?php if (isset($patients[$cnt])): ?>

                                <?php $patient = $patients[$cnt]; ?>

                                <td><?= $cnt + 1; ?>.</td>

                                <td><?= Html::a($patient->fullName, ['/patient/view', 'id' => $patient->id], ['title' => $patient->user->fullName]) ?></td>

                                <td><span title="<?= date("H:i:s", $patient->created_at); ?>"><?= Yii::$app->formatter->asDate($patient->created_at, 'long'); ?></span></td>

                            <?php else: ?>

                                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

                            <?php endif; ?>

                        </tr>

                    <?php endfor; ?>

                </table>

                <p><br /><?= Html::a(Yii::t('frontend-views', 'View patients').' &raquo;', ['/patient/index'], ['class' => ['btn btn-success']]) ?></p>

            </div>

            <div class="col-lg-4">

                <h2><?= Yii::t('frontend-views', 'Examinations') ?></h2>

                <p><?= Yii::t('frontend-views', 'Recently added examinations') ?>:</p>

                <table class="homepage-table">

                    <?php for ($cnt = 0; $cnt < 5; $cnt++): ?>

                    <tr>

                            <?php if (isset($examinations[$cnt])): ?>

                                <?php $examination = $examinations[$cnt]; ?>

                                <td><?= $cnt + 1; ?>.</td>

                                <td><?= Html::a($examination->patient->fullName, ['/examination/view', 'id' => $examination->id], ['title' => $examination->patient->user->fullName]) ?></td>

                                <td><span title="<?= date("H:i:s", $examination->created_at); ?>"><?= Yii::$app->formatter->asDate($examination->created_at, 'long'); ?></span></td>

                            <?php else: ?>

                                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

                            <?php endif; ?>

                    </tr>

                    <?php endfor; ?>

                </table>

                <p><br /><?= Html::a(Yii::t('frontend-views', 'View examinations').' &raquo;', ['/examination/index'], ['class' => ['btn btn-success']]) ?></p>

            </div>

            <div class="col-lg-4">

                <h2><?= Yii::t('frontend-views', 'Results') ?></h2>

                <p><?= Yii::t('frontend-views', 'Recently added results') ?>:</p>

                <table class="homepage-table">

                    <?php for ($cnt = 0; $cnt < 5; $cnt++): ?>

                        <tr>

                            <?php if (isset($results[$cnt])): ?>

                                <?php $result = $results[$cnt]; ?>

                                <td><?= $cnt + 1; ?>.</td>

                                <td><?= Html::a($result->examination->patient->fullName, ['/result/view', 'id' => $result->id], ['title' => $result->examination->patient->user->fullName]) ?></td>

                                <td><span title="<?= date("H:i:s", $result->created_at); ?>"><?= Yii::$app->formatter->asDate($result->created_at, 'long'); ?></span></td>

                            <?php else: ?>

                                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

                            <?php endif; ?>

                        </tr>

                    <?php endfor; ?>

                </table>

                <p><br /><?= Html::a(Yii::t('frontend-views', 'View results').' &raquo;', ['/result/index'], ['class' => ['btn btn-success']]) ?></p>

            </div>

        </div>

    </div>

</div>
