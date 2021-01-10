<?php

namespace app\modules\admin\controllers;
use yii;
use app\models\User;
use yii\web\Controller;
use yii\data\Pagination;
/**
 * Default controller for the `admin` module
 */
class DefaultController extends AdminController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $query = User::find();
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 10, 'forcePageParam' => false, 'pageSizeParam' => false]);
        $users = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        
        return $this->render('index', [
            'users' => $users,
            'pages' => $pages,
        ]);
    }

    public function actionUser($id){
        $model = $this->findModel($id);
        return $this->render('user', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id){
        $model =$this->findModel($id);
            if($model->id != Yii::$app->user->identity->id && $model->role != 'admin'){
                if($model->status){
                    $model->status = 0;
                } 
                else{
                    $model->status = 1;
                } 
                $model->save();
            }
        return $this->redirect(['user','id'=>$id]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
