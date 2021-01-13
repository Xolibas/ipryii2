<?php

namespace app\models\services;

use Yii;
use app\models\forms\CommentForm;
use app\models\Comment;
use app\models\repositories\CommentRepository;

class CommentService
{
    private $comments;

    public function __construct(CommentRepository $comments)
    {
        $this->comments = $comments;
    }

    public function create(CommentForm $form, $post_id): Comment
    {
        $comment = Comment::create(
            $form->text,
            $post_id
        );
        $this->comments->save($comment);

        return $comment;
    }

    public function edit($id, CommentForm $form): void
    {
        $comment = $this->comments->get($id);

        $comment->edit(
            $form->text
        );
        $this->comments->save($comment);
    }

    public function remove($id): void
    {
        $comment = $this->comments->get($id);
        if ($comment->user_id != Yii::$app->user->id)
        {
            throw new \DomainException('It’s not your comment!');
        }
        else
        {
            $this->comments->remove($comment);
        }
    }

}