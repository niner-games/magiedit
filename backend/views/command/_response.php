<?php

/** @var array|string $response */

use yii\helpers\Html;

?>

<div class="rendered-command-response">

    <?php if (isset($options)): ?> <?= HTML::beginTag('div', $options) ?> <?php endif; ?>

        <?php if (is_array($response)): ?>

            <?php $i = 1; $cnt = count($response); ?>

            <?php foreach ($response as $key => $value): ?>

                <?= $key ?>: <strong><?= $value ?></strong><?php if ($i < $cnt): ?>,<?php endif; ?>

                <?php if (isset($split) && $split): ?><br /><?php endif; ?>

                <?php ++$i; ?>

            <?php endforeach; ?>

        <?php else: ?>

            <?= $response; ?>

        <?php endif; ?>

    <?php if (isset($options)): ?> <?= HTML::endTag('div') ?> <?php endif; ?>

</div>