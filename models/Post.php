<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property string $id
 * @property string|null $title
 * @property string|null $text
 * @property int|null $status
 * @property int|null $user_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Comments[] $comments
 * @property User $user
 */
class Post extends \yii\db\ActiveRecord
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    public function isActive(): bool
    {
        return $this->status === self::ACTIVE;
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function create(string $title, string $text, string $status)
    {
        $post = new Post();
        $post->title = $title;
        $post->text = $text;
        $post->user_id = Yii::$app->user->id;
        $post->id = uniqid();
        $post->status = $status;
        $post->created_at = date('y-m-d h:i:s');

        return $post;
    }

    public function edit(string $title, string $text, string $status): void
    {
        $this->title = $title;
        $this->text = $text;
        $this->updated_at = date('y-m-d h:i:s');
        $this->status = $status;
    }
}
