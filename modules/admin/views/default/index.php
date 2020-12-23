<?php

use yii\helpers\Html;
use yii\data\Pagination;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admin Panel';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>
<table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Username</th>
      <th scope="col">E-mail</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($users as $user): ?>
        <tr>
      <th><?= $user->id ?></th>
      <td><?= $user->username ?></td>
      <td><?= $user->email ?></td>
      <th></th>
      <th><a href="<?= yii\helpers\Url::toRoute(['/admin/default/user','id'=>$user->id]); ?>" class="eye-open">View</a></th>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
    <?= \yii\widgets\LinkPager::widget([
        'pagination' => $pages,
        ]);
    ?>

</div>
