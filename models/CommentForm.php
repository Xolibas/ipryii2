<?php

namespace app\models;
use yii\base\Model;


class CommentForm extends Model{
    public $text;

    public function attributeLabels(){
        return [
            'text' => 'Text',
        ];
    }
}