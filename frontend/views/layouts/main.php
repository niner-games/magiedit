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
$appTitle = 'MagiEdit';

$this->registerCss("
    .alert {
        position: relative;
        border: none !important;
        text-align: center;
        border-radius: 0;
        margin-bottom: 0;
        color: white !important;
    }
    
    .alert-success {
        background-color: rgb(28, 110, 48);
        color: #2d572d;
    }
    
    .alert-danger {
        background-color: rgb(188, 106, 47);
    }
    
    .alert-warning {
        background-color: #fff3cd;
        color: black !important;
    }
    
    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    
    @media (max-width: 768px) {
        .navbar {
            position: relative !important;
        }
    }
");

$this->registerJs("
    document.querySelectorAll('.alert').forEach(alertBox => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertBox);
            bsAlert.close();
        }, 5000);
    });
");

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($appTitle) ?> &ndash; <?= Yii::t('views', 'Where visual novels and gamebooks begin their journey') ?></title>

    <?= Html::tag('link', '', [
        'rel' => 'icon',
        'type' => 'image/x-icon',
        'href' => Yii::getAlias('@web') . '/images/favicon.ico',
    ]) ?><?="\n" ?>
    <?= Html::tag('link', '', [
        'rel' => 'icon',
        'type' => 'image/png',
        'sizes' => '512x512',
        'href' => Yii::getAlias('@web') . '/images/favicon.png',
    ]) ?><?="\n" ?>
    <?= Html::tag('link', '', [
        'rel' => 'apple-touch-icon',
        'href' => Yii::getAlias('@web') . '/images/favicon-apple.png',
    ]) ?><?="\n" ?>
    <?= Html::tag('link', '', [
        'rel' => 'icon',
        'type' => 'image/svg+xml',
        'href' => Yii::getAlias('@web') . '/images/favicon.svg',
    ]) ?>


    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?= Alert::widget() ?>

<header>
    <?php NavBar::begin([
        'brandLabel' => Html::img('@web/images/logo-oneliner-white.png', [
            'alt' => 'MagiEdit logo',
            'style' => 'height: 42px;'
        ]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark',
        ]]);

        $items = [];

        if (Yii::$app->user->identity && Yii::$app->user->identity->getIsAdmin()) {
            $items[] = [
                'url' => ['/user/index'],
                'label' => Yii::t('views', 'Users')
            ];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
            'items' => $items,
        ]);

        if (Yii::$app->user->isGuest) {
            echo Html::a(Yii::t('views', 'Sign Up'), ['/account/signup'], ['class' => ['btn btn-secondary']]);

            echo '&nbsp;';

            echo Html::a(Yii::t('views', 'Log In'), ['/account/login'], ['class' => ['btn btn-success']]);
        } else {
            echo Html::beginForm(['/account/logout'], 'post', ['class' => 'd-flex'])
                . Html::submitButton(
                    Yii::t('views', 'Log Out'),
                    ['class' => 'btn btn-success']
                )
                . Html::endForm();
        }

    NavBar::end(); ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container" style="padding: 15px 20px;">

        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'] ?? [],
            'homeLink' => [
                'url' => ['/site/index'],
                'label' => 'MagiEdit'
            ],
            'options' => [
                'style' => '--bs-breadcrumb-divider: "⇢"', // https://forum.yiiframework.com/t/how-to-modify-breadcrumbs-separator-character/135649/2?u=trejder
            ]
        ]) ?>

        <?= $content ?>

    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start mb-0">

            Copyright &copy; <?= date('Y') ?> by

            <?= Html::a('MagiEdit', 'https://magiedit.com/', ['rel' => "external"]) ?>.

            <span class="d-none d-md-inline">

                <?= Yii::t('views', 'Version') ?>:
                <?= ApplicationVersion::get() ?>.

            </span>

        <p class="float-end mb-0">

            <?= Html::a(Yii::t('views', 'polski'), Url::current(['language' => 'pl'])) ?> |
            <?= Html::a(Yii::t('views', 'English'), Url::current(['language' => 'en'])) ?>

        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
