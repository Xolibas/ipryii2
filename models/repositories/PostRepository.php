<?php

namespace app\models\repositories;

use app\models\Post;

class PostRepository
{
    public function save(Post $post): Post
    {
        if (!$post->save()){
            throw new \RuntimeException('Saving error.');
        }
        return $post;
    }

    public function remove(Post $post): void
    {
        if(!$post->isActive())
        {
            if (!$post->delete()) {
                throw new \RuntimeException('Removing error.');
            }
        }
    }

    public function get($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}