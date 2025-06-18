<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use common\widgets\ApplicationVersion;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$appAssetBundle = AppAsset::register($this);
$appTitle = Yii::t('frontend-application', "TC-MED's Rehabilitation Platform");

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($appTitle) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => $appTitle,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => [
        [
            'url' => ['/site/index'],
            'label' => Yii::t('frontend-views', 'Start')
        ],
        [
            'url' => ['/patient/index'],
            'label' => Yii::t('frontend-views', Yii::t('frontend-views', 'Patients')),
        ],
        [
            'url' => ['/examination/index'],
            'label' => Yii::t('frontend-views', Yii::t('frontend-views', 'Examinations')),
        ],
        [
            'url' => ['/result/index'],
            'label' => Yii::t('frontend-views', Yii::t('frontend-views', 'Results')),
        ],
    ]]);

    if (Yii::$app->user->isGuest) {
        echo Html::a(Yii::t('frontend-views', 'Signup'), ['/site/signup'], ['class' => ['btn btn-secondary']]);

        echo '&nbsp;';

        echo Html::a(Yii::t('frontend-views', 'Login'), ['/site/login'], ['class' => ['btn btn-success']]);
    } else {
        /*
         * This is ugly, but there seems to be no other way of doing this:
         * https://forum.yiiframework.com/t/link-to-backend-application/71167/10
         */
        $backendUrl = str_replace('frontend', 'backend', Url::to(['site/index'], true));
        echo Html::a(Yii::t('frontend-views', 'Control Panel'), $backendUrl, ['class' => ['btn btn-secondary']]);

        echo '&nbsp;';

        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                Yii::t('frontend-views', 'Logout'),
                ['class' => 'btn btn-success']
            )
            . Html::endForm();
    }

    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">

        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'] ?? [],
            'homeLink' => [
                'url' => ['/site/index'],
                'label' => Yii::t('frontend-views', 'Start')
            ],
            'options' => [
                'style' => '--bs-breadcrumb-divider: "⇢"', // https://forum.yiiframework.com/t/how-to-modify-breadcrumbs-separator-character/135649/2?u=trejder
            ]
        ]) ?>

        <?= Alert::widget() ?>

        <?= $content ?>

    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">

            <?php
                echo \Yii::t('frontend-views', 'Copyright &copy; 2022-{current-year} by', [
                    'current-year' => date('Y'),
                ]);
            ?>

            <?= Html::a('TC-MED sp. z o.o.', 'http://tc-med.pl/', ['rel' => "external"]) ?>
            <?= Yii::t('frontend-views', 'All rights reserved') ?>.

            <?= Yii::t('frontend-views', 'Version') ?>:
            <?= ApplicationVersion::get() ?>.
        </p>
        <p class="float-end">
            <?= Html::a(Yii::t('frontend-views', 'polski'), Url::current(['language' => 'pl'])) ?> |
            <?= Html::a(Yii::t('frontend-views', 'English'), Url::current(['language' => 'en'])) ?>
        </p>
    </div><br /><br />
    <div class="container" style="text-align: center">
        <p><img alt="<?= Yii::t('frontend-views', 'European Funds icons') ?>" style="width: 100%;" src="<?= $appAssetBundle->baseUrl.'/footer.png' ?>" /></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
