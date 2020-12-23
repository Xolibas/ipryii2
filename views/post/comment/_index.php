<?php 
    echo $model->user->username;
?>
<?php foreach($model as $comment) : ?>
<table class="table table-striped table-inverse">
  <thead>
    <tr>
      <th><?= $comment->user->username; ?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?= $comment->text; ?></td>
    </tr>
    <tr>
        <td><?= $comment->created_at; ?></td>
    </tr>
    <tr>
    <?php if($comment->user_id == Yii::$app->user->identity->id):?>
        <th><a href=<?= yii\helpers\Url::toRoute(['/post/deletec','id'=>$comment->id,'post_id'=>$comment->post_id]); ?> class="btn btn-danger">Delete</a></th>
    <?php endif; ?>
    </tr>
  </tbody>
</table>
<?php endforeach; ?>
<?= \yii\widgets\LinkPager::widget([
        'pagination' => $pages,
        ]);
    ?>