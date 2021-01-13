<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\Comment;
use app\models\searchs\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use app\models\repositories\PostRepository;
use app\models\repositories\CommentRepository;
use app\models\services\CommentService;
use app\models\services\PostService;
use app\models\forms\PostForm;
use app\models\forms\CommentForm;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    private $postService;
    private $postRepository;
    private $commentService;
    private $commentRepository;

    public function __construct($id, $module, PostService $service, PostRepository $repository, CommentRepository $crepository, CommentService $cservice, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->postService = $service;
        $this->postRepository = $repository;
        $this->commentService = $cservice;
        $this->commentRepository = $crepository;
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $newcomment = new CommentForm();
        $model = $this->postRepository->get($id);
        if($model->user_id==$this->getUserId() || $model->isActive()){
            if($newcomment->load(Yii::$app->request->post())&&$newcomment->validate())
            {
                try {
                    $comment = $this->commentService->create($newcomment,$id);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
            $query = Comment::find()->where(['post_id'=>$id]);
            $newcomment = new CommentForm();
            $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 5, 'forcePageParam' => false, 'pageSizeParam' => false]);
            $comments = $query->offset($pages->offset)->limit($pages->limit)->all();
            return $this->render('view', [
                'model' => $model,
                'comments' => $comments,
                'pages'=>$pages,
                'newcomment' => $newcomment,
            ]);
        }
        else{
            return $this->redirect(Url::to(['/posts']));
        }
    }

    // public function actionCreate()
    // {
    //     $model = new Post();

    //     if ($model->load(Yii::$app->request->post())) {
    //         $model->user_id = $this->getUserId();
    //         $model->id = uniqid();
    //         $model->created_at = date('y-m-d h:i:s');
    //         if($model->save()){
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         }
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionCreate()
    {
        $form = new PostForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            try {
                $post = $this->postService->create($form);
                return $this->redirect(['view', 'id' => $post->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);
    //     if($model->user_id==$this->getUserId()){
    //         if ($model->load(Yii::$app->request->post())) {
    //             $model->updated_at = date('y-m-d h:i:s');
    //             if($model->save())
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         }
    //     }
    //     else{
    //         return $this->redirect(Url::to(['/posts']));
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionUpdate($id)
    {
        $post = $this->postRepository->get($id);
        $form = new PostForm($post);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->postService->edit($post->id, $form);
                return $this->redirect(Url::to(['view', 'id' => $post->id]));
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $model = $this->findModel($id);
    //     if($model->user_id==$this->getUserId() && !$model->status)
    //     $model->delete();
    //     return $this->redirect(Url::to(['/index']));
    // }

    public function actionDelete($id)
    {
        $this->postService->remove($id);
        return $this->redirect(Url::to(['/posts']));
    }

    // public function actionDeletec($id,$post_id)
    // {
    //     $model = Comment::findOne($id);
    //     if($model->user_id==$this->getUserId())
    //     $model->delete();
    //     return $this->redirect(Url::to(['view','id'=>$post_id]));
    // }

    public function actionDeletec($id,$post_id)
    {
        $this->commentService->remove($id);
        return $this->redirect(Url::to(['view','id'=>$post_id]));
    }

    // /**
    //  * Finds the Post model based on its primary key value.
    //  * If the model is not found, a 404 HTTP exception will be thrown.
    //  * @param string $id
    //  * @return Post the loaded model
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // protected function findModel($id)
    // {
    //     if (($model = Post::findOne($id)) !== null) {
    //         return $model;
    //     }

    //     throw new NotFoundHttpException('The requested page does not exist.');
    // }

    protected function getUserId(){
        return Yii::$app->user->identity->id;
    }
}
