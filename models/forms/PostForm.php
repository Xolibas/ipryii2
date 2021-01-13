<?php

namespace app\models\forms;

use yii\base\Model;


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
            [['id'], 'required'],
            ['text','trim'],
            ['text','required'],
            [['status', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'title','text'], 'string', 'max' => 255],
            [['id'], 'unique'],
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