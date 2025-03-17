<?php

/** @var yii\web\View $this */
/** @var yii\db\ActiveQuery $commands */
/** @var yii\db\ActiveQuery $users */
/** @var yii\db\ActiveQuery $devices */

use yii\bootstrap5\Html;

$this->registerJs("
    $('a').tooltip();
    $('span').tooltip();
");

?>

<div class="site-index" xmlns="http://www.w3.org/1999/html">

    <div class="p-5 mb-4 bg-transparent rounded-3">

        <div class="container-fluid py-5 text-center">

            <h1 class="display-4"><?= Yii::t('backend-views', 'Control Panel'); ?></h1>

            <p class="fs-5">

                <?= Yii::t('backend-views', 'Innovative solutions for remote monitoring and rehabilitation of postural defects') ?>
                (WND-RPSL.01.02.00-24-0128/21-010)

            </p>

    </div>

    <div class="body-content">

        <div class="row">

            <div class="col-lg-4">

                <h2><?= Yii::t('backend-views', 'Users') ?></h2>

                <p><?= Yii::t('backend-views', 'Recently added users') ?>:</p>

                <table class="homepage-table">

                    <?php for ($cnt = 0; $cnt < 5; $cnt++): ?>

                        <tr>

                            <?php if (isset($users[$cnt])): ?>

                                <?php $user = $users[$cnt]; ?>

                                <td><?= $cnt + 1; ?>.</td>

                                <td><?= Html::a($user->fullName, ['/user/view', 'id' => $user->id]) ?></td>

                                <td><span title="<?= date("H:i:s", $user->created_at); ?>"><?= Yii::$app->formatter->asDate($user->created_at, 'long'); ?></span></td>

                            <?php else: ?>

                                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

                            <?php endif; ?>

                        </tr>

                    <?php endfor; ?>

                </table>

                <p><br /><?= Html::a(Yii::t('backend-views', 'View users').' &raquo;', ['/user/index'], ['class' => ['btn btn-success']]) ?></p>

            </div>

            <div class="col-lg-4">

                <h2><?= Yii::t('backend-views', 'Devices') ?></h2>

                <p><?= Yii::t('backend-views', 'Recently added devices') ?>:</p>

                <table class="homepage-table">

                    <?php for ($cnt = 0; $cnt < 5; $cnt++): ?>

                    <tr>

                            <?php if (isset($devices[$cnt])): ?>

                                <?php $device = $devices[$cnt]; ?>

                                <td><?= $cnt + 1; ?>.</td>

                                <td><?= Html::a($device->name, ['/device/view', 'id' => $device->id]) ?></td>

                                <td><span title="<?= date("H:i:s", $device->created_at); ?>"><?= Yii::$app->formatter->asDate($device->created_at, 'long'); ?></span></td>

                            <?php else: ?>

                                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

                            <?php endif; ?>

                    </tr>

                    <?php endfor; ?>

                </table>

                <p><br /><?= Html::a(Yii::t('backend-views', 'View devices').' &raquo;', ['/device/index'], ['class' => ['btn btn-success']]) ?></p>

            </div>

            <div class="col-lg-4">

                <h2><?= Yii::t('backend-views', 'Commands') ?></h2>

                <p><?= Yii::t('backend-views', 'Recently parsed commands') ?>:</p>

                <table class="homepage-table">

                    <?php for ($cnt = 0; $cnt < 5; $cnt++): ?>

                        <tr>

                            <?php if (isset($commands[$cnt])): ?>

                                <?php $command = $commands[$cnt]; $response = $command->getParsedData($command->body); ?>

                                <?php $tooltip = (is_array($response) && count($response) > 0) ? [
                                    'data-bs-html' => 'true',
                                    'title' => $this->render('/command/_response', [
                                        'split' => true,
                                        'response' => $response,
                                        'options' => ['style' => "text-align: left"]
                                    ]),
                                ] : []; ?>

                                <td><?= $cnt + 1; ?>.</td>

                                <td><?= Html::a($command->getAttributeDesc('type').' <span style="font-weight: normal">('.$command->getAttributeDesc('status').')</span>', [
                                    '/command/view',
                                    'id' => $command->id
                                ], $tooltip) ?></td>

                                <?php if ($command->updated_at !== $command->created_at): ?>

                                    <td><span title="<?= Yii::$app->formatter->asDate($command->updated_at, 'long'); ?>"><?= date("H:i:s", $command->updated_at); ?></span></td>

                                <?php else: ?>

                                    <td>---</td>

                                <?php endif; ?>

                            <?php else: ?>

                                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>

                            <?php endif; ?>

                        </tr>

                    <?php endfor; ?>

                </table>

                <p><br /><?= Html::a(Yii::t('backend-views', 'View commands').' &raquo;', ['/command/index'], ['class' => ['btn btn-success']]) ?></p>

            </div>

        </div>

    </div>

</div>
