<?php

namespace app\modules\admin\controllers;
use yii;
use app\models\User;
use app\models\searchs\UserSearch;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\Url;
use app\models\repositories\UserRepository;
use app\models\services\LoginService;
/**
 * Default controller for the `admin` module
 */
class DefaultController extends AdminController
{
    private $userService;
    private $userRepository;

    public function __construct($id, $module, LoginService $service, UserRepository $repository, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->userService = $service;
        $this->userRepository = $repository;
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUser($id){
        $model = $this->userRepository->get($id);
        return $this->render('user', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id){
        $this->userService->activate($id);
        return $this->redirect(Url::to(['user','id'=>$id]));
    }
}
