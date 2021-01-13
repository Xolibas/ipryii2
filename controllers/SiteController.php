<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\User;
use yii\helpers\Url;
use app\models\forms\LoginForm;
use app\models\services\LoginService;
use app\models\services\SignupService;
use app\models\forms\SignupForm;

class SiteController extends Controller
{
    private $loginService;
    private $signupService;

    public function __construct($id, $module, LoginService $loginService, SignupService $signupService,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->signupService = $signupService;
        $this->loginService = $loginService;
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','cabinet'],
                'rules' => [
                    [
                        'actions' => ['logout','cabinet'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(Url::to(['/site/login']));
        }
        else return $this->redirect(Url::to(['/posts']));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try{
                $this->loginService->login($model);
                return $this->goBack();
            }catch (\DomainException $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $user = $this->signupService->signup($form);
            if (Yii::$app->getUser()->login($user)) {
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $form,
        ]);
    }

    public function actionCabinet(){
        return $this->render('cabinet');
    }
}