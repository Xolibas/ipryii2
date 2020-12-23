<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\Comment;
use app\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
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
                        'roles' => ['@','?'],
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
        $newcomment = new Comment();
        $model = $this->findModel($id);
        if($model->user_id==$this->getUserId() || $model->status==1){
            if($newcomment->load(Yii::$app->request->post())&&(!Yii::$app->user->isGuest)){
                $newcomment->user_id = $this->getUserId();
                $newcomment->post_id = $id;
                $newcomment->created_at = date('y-m-d h:i:s');
                $newcomment->save();
            }
            $query = Comment::find()->where(['post_id'=>$id]);
            $newcomment = new Comment();
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
            return $this->redirect('/posts');
        }
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post())) {
            $model->title = $_POST['Post']['title'];
            $model->text = $_POST['Post']['text'];
            $model->status = (int)$_POST['Post']['status'];
            $model->user_id = $this->getUserId();
            $model->id = uniqid();
            $model->created_at = date('y-m-d h:i:s');
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->user_id==$this->getUserId()){
            if ($model->load(Yii::$app->request->post())) {
                $model->updated_at = date('y-m-d h:i:s');
                if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        else{
            return $this->redirect(['/posts']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->user_id==$this->getUserId() && !$model->status)
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionDeletec($id,$post_id)
    {
        $model = Comment::findOne($id);
        if($model->user_id==$this->getUserId())
        $model->delete();
        return $this->redirect(['view','id'=>$post_id]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getUserId(){
        return Yii::$app->user->identity->id;
    }
}
