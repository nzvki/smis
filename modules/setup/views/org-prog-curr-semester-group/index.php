<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\OrgProgCurrSemesterGroup;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrSemesterGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Program Curriculum Semester Groups';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-curr-semester-group-index">


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
    '<i class="bi bi-plus-lg"></i> Add Program Curriculum Semester Group',
    ['create'],
    ['class' => 'btn btn-lg btn-primary']
)
?>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>


            <?= GridView::widget([
'dataProvider' => $dataProvider,
'filterModel' => $searchModel,
'export' => false,
'columns' => [
    ['class' => 'kartik\grid\SerialColumn'],

    // 'prog_curriculum_sem_group_id',
    [
        'attribute' => 'curriculum',
        'label' => ' Curriculum Semester',
        'value' => function ($model) {
            $acad = $model['progCurriculumSemester']['acadSessionSemester']['acad_session_semester_desc'];
            $prog = $model['progCurriculumSemester']['progCurriculum']['prog_curriculum_desc'];
            return $acad. '-'. $prog;
        }
    ],

    [
        'attribute' => 'studyGroup',
        'label' => 'Study Centre Group',
        'value' => function ($model) {
            $name = $model['studyCentreGroup']['studyCentre']['study_centre_name'];
            $group = $model['studyCentreGroup']['studyGroup']['study_group_name'];
            return $name. '-'. $group;
        }
    ],
    [
        'attribute' => 'start_date',
        'value' => function ($model) {
            return Yii::$app->formatter->asDate($model->start_date, 'php:d-M-Y');
        },
    ],
    [
        'attribute' => 'end_date',
        'value' => function ($model) {
            return Yii::$app->formatter->asDate($model->end_date, 'php:d-M-Y');
        },
    ],
    [
        'attribute' => 'registration_deadline',
        'value' => function ($model) {
            return Yii::$app->formatter->asDate($model->registration_deadline, 'php:d-M-Y');
        },
    ],
    [
        'attribute' => 'display_date',
        'value' => function ($model) {
            return Yii::$app->formatter->asDate($model->display_date, 'php:d-M-Y');
        },
    ],
    [
        'attribute' => 'programmeLevel',
        'label' => 'Programme Level',
        'value' => 'programmeLevel.programme_level_name'
    ],
    'status',

    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{update} ',
        'buttons' => [
            'update' => function ($url, $model, $key) {
                return  Html::a(' Update', ['/setup/org-prog-curr-semester-group/update','prog_curriculum_sem_group_id' => $model->prog_curriculum_sem_group_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
            },
        ]

    ],
],
            ]); ?>

        </div>
    </div>
</div>
