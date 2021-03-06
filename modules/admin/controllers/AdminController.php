<?php

namespace app\modules\admin\controllers;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Default controller for the `admin` module
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->role == 'admin') {
                                return true;
                            }
                            return false;
                        }
                    ],
                ],
            ],
        ];
    }
}
