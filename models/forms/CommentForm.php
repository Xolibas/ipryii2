<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Post;
use app\models\User;


class CommentForm extends Model
{
    public $id;
    public $text;
    public $user_id;
    public $post_id;

    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['text', 'post_id'], 'string', 'max' => 255],
            [['text'],'required'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'user_id' => 'User ID',
            'post_id' => 'Post ID',
        ];
    }
}