<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if($model->user_id==Yii::$app->user->identity->id): ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'text:ntext',
            'status',
            'user_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>
    <?php if($model->status): ?>
    <?= \ymaker\social\share\widgets\SocialShare::widget([
        'configurator'  => 'socialShare',
        'url'           => \yii\helpers\Url::to('http://ipryii2.net:81/post/'.$model->id, true),
        'title'         => $model->title,
    ]); ?>
    <?php endif; ?>
    <?= $this->render('comment/_create',[
        'model' => $newcomment,
    ]) ?>
    <?= $this->render('comment/_index',[
        'model' => $comments,
        'pages' => $pages,
    ]) ?>

</div>
