<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\User;


class PostForm extends Model
{
    public $id;
    public $title;
    public $text;
    public $status;
    public $created_at;
    public $updated_at;
    public $user_id;

    public function rules()
    {
        return [
            [['text', 'title'], 'required'],
            [['id', 'title','text'], 'string', 'max' => 255],
            [['created_at', 'updated_at', 'status'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'status' => 'Status',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}