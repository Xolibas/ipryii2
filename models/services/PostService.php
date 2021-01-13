<?php

namespace app\models\services;

use app\models\forms\PostForm;
use app\models\Post;
use app\models\repositories\PostRepository;

class PostService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function create(PostForm $form): Post
    {
        $post = Post::create(
            $form->title,
            $form->text,
            $form->status
        );
        if(!$post->save()){
            throw new \RuntimeException('Saving error.');
        }

        return $post;
    }

    public function edit($id, PostForm $form): void
    {
        $post = $this->posts->get($id);

        $post->edit(
            $form->title,
            $form->text,
            $form->status
        );
        if(!$post->save()){
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove($id): void
    {
        $post = $this->posts->get($id);
        if(!$post->isActive())
        {
            $this->posts->remove($post);
        }
    }

}