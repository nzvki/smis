<?php

namespace app\modules\examManagement\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\ProgCurrCourseGroup;
use app\models\search\ProgCurrCourseGroupSearch;
use yii\web\Request;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;

/**
 * ProgCurrCourseGroupController implements the CRUD actions for ProgCurrCourseGroup model.
 */
class ProgCurrCourseGroupController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


    /**
     * Updates an existing ProgCurrLevelRequirement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prog_id Prog ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($course_group_id)
    
    {
        $id = $this->request->get('course_group_id');    
        $model = ProgCurrCourseGroup::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('view', [
                'model' => $model   ,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single ProgCurrCourseGroup model.
     * @param int $course_group_id Prog Curr Level Req ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($course_group_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($course_group_id),
        ]);
    }

    /**
     * Creates a new ProgCurrCourseGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProgCurrCourseGroup();

        if ($this->request->isPost) {            
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success', " Prog Curr Course Group Created!");
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
      // $id = $this->request->get('prog_curr_group_requirement_id');
       
       
       
        $model = 
        ProgCurrCourseGroup::find()
        ->select([
           'prog_curr_course_group.course_group_id',
           'prog_curr_course_group.course_group_desc',
           'prog_curr_course_group.course_group_name',
           'prog_curr_course_group.course_group_type',
           ])
      //  ->innerJoinWith('progCurrCourseName')
      //  ->where([
      //     'prog_curr_group_req_course.prog_curr_group_requirement_id' => $id
        ->asArray()->all();


        
        $provider = new ArrayDataProvider([
           'allModels' => $model,
           'pagination' => [
               'pageSize' => 10,
           ],
       ]);

        // Render the view or perform further operations with the related models
        return $this->render('index', [
            'searchModel' => $model,
            'dataProvider' => $provider,
        ]);
    }

    public function actionSave()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $post = \Yii::$app->request->post();
                
            $progCurrCourseGroup = new ProgCurrCourseGroup();
            $progCurrCourseGroup->course_group_name = strtoupper($post['group-name']);
            $progCurrCourseGroup->course_group_desc = strtoupper($post['group-desc']);
            $progCurrCourseGroup->course_group_type = strtoupper($post['group-type']);


            $duplicate = ProgCurrCourseGroup::find()
            ->where(['course_group_id' => $progCurrCourseGroup->course_group_id, 'course_group_name' => $progCurrCourseGroup->course_group_name ]);

            $duplicates = $duplicate->count();

         if ($duplicates > 0) {
            // Duplicate study level found for the course
            throw new \Exception('Prog Course Group not saved. Duplicate found for the Course group name');
         }

            if(!$progCurrCourseGroup->save()){
                if(!$progCurrCourseGroup->validate()){
                    throw new \Exception($this->getModelErrors($progCurrCourseGroup->getErrors()));
                }else{
                    throw new \Exception('Prog Curr Course Group not saved.');
                }
            }
            $transaction->commit();
           // dd('success');
//Redirect to list page
return $this->render('view', [
    'model' => $progCurrCourseGroup,
]);
        }catch(\Exception $ex){
            $transaction->rollback();
            dd($ex->getMessage());
            // redirect error page
        }
    }

    private function getModelErrors(array $modelErrors): string
    {
        $errorMsg = '';
        foreach ($modelErrors as $attributeErrors){
            for($i=0; $i < count($attributeErrors); $i++){
                $errorMsg .= ' ' . $attributeErrors[$i];
            }
        }
        return $errorMsg;
    }

    
    /**
     * Finds the ProgCurrCourseGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $course_group_id Course Prerequisite ID
     * @return ProgCurrCourseGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($course_group_id)
    {
        if (($model = ProgCurrCourseGroup::findOne(['course_group_id' => $course_group_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
