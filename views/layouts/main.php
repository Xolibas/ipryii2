<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
<div class="i_con">
    <ul class="nav navbar-nav">
        <?php if(Yii::$app->user->isGuest):?>
            <li><a href="<?= Url::toRoute(['/site/login'])?>">Login</a></li>
            <li><a href="<?= Url::toRoute(['/site/signup'])?>">Register</a></li>
        <?php else: ?>
            <li><a href="<?= Url::toRoute(['/site/logout'])?>">Logout</a></li>
            <li><a href="<?= Url::toRoute(['/site/cabinet'])?>" ><?= Yii::$app->user->identity->username ?></a></li>
            <li><a href="<?= Url::toRoute(['/post/index'])?>">Post</a></li>
            <?php if(Yii::$app->user->identity->role == 'admin'): ?>
                <li><a href="<?= Url::toRoute(['/admin'])?>">Admin</a></li>
            <?php endif; ?>
        <?php endif;?>
    </ul>
</div>

    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
