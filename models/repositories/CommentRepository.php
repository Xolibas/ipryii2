<?php

namespace app\models\repositories;


use app\models\Comment;
use yii\web\NotFoundHttpException;

class CommentRepository
{
    public function save(Comment $comment): Comment
    {
        if (!$comment->save()){
            throw new \RuntimeException('Saving error.');
        }
        return $comment;
    }

    public function remove(Comment $comment): void
    {
        if (!$comment->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function get($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}