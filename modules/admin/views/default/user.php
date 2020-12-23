<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->username;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if($model->id != Yii::$app->user->identity->id && $model->role != 'admin') : ?>
        <?php if($model->status): ?>
            <a href="<?= yii\helpers\Url::toRoute(['/admin/default/update','id'=>$model->id]); ?>" class="btn btn-danger">Deactivate</a>
        <?php else: ?>
            <a href="<?= yii\helpers\Url::toRoute(['/admin/default/update','id'=>$model->id]); ?>" class="btn btn-success">Activate</a>
        <?php endif; ?>
    <?php endif; ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email',
            'status',
            'role',
        ],
    ]) ?>

</div>
