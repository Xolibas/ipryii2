<?php

namespace app\models\forms;

use yii\base\Model;

class SignupForm extends Model
{

    public $username;
    public $email;
    public $password;


    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password','match', 'pattern' => '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', 'message' => 'Password must have minimum one numeric and minimum one character in uppercase.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Login',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
}