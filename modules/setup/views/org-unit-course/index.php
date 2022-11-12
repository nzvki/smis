<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgUnitCourse;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgUnitCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unit Courses';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-unit-course-index">
    <div class="card" >
        <div class="card-body">

            <div class="d-flex justify-content-end">
                <?= Html::a('<i class="bi bi-plus-lg"></i> Create Unit Course', ['create'], ['class' => 'btn btn-primary']) ?>
            </div>
            <h3 class="card-title mb-3">
  <?= Html::encode($this->title) ?></h3>



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'org_unit_course_id',
//            'course_id',
            [
                'attribute' => 'course',
                'label' => 'Course Name',
                'value' => 'course.course_name',
            ],
            [
                'attribute' => 'orgUnit',
                'label' => 'Organisation Unit',
                'value' => 'orgUnit.unit_name',
            ],
            [
                'attribute' => 'teachingUnit',
                'label' => 'Teaching Unit',
                'value' => 'teachingUnit.unit_name',
            ],

//            'org_unit_id',


           // 'org_teaching_id',
            [
                'attribute' => 'start_date',
                'value' => function ($model) {
                    return strtoupper(Yii::$app->formatter->asDate($model->start_date, 'php:d-M-yy'));
                },
            ], 
            [
                'attribute' => 'end_date',
                'value' => function ($model) {
                    return strtoupper(Yii::$app->formatter->asDate($model->end_date, 'php:d-M-yy'));
                },
            ], 
           // 'user_id',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, OrgUnitCourse $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'org_unit_course_id' => $model->org_unit_course_id]);
//                 }
//            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a(' Update', ['/setup/org-unit-course/update','org_unit_course_id' => $model->org_unit_course_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                    },
                ]

            ],

        ],
    ]); ?>


</div>
</div>
</div>
