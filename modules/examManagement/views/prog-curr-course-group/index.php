<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use app\models\ProgCurrLevelRequirement;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProgCurrLevelRequirementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Course Groups';
$this->params['breadcrumbs'][] = ['label' => 'Exam Management', 'url' => ['/exam-management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Prog-Curr-Level-Requirement-index">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <?= Html::a(
                    '<i class="bi bi-plus-lg"></i> Create  Course Groups',
                    ['create'],
                    ['class' => 'btn btn-lg btn-primary']) 
                ?>
            </div>
            <h3 class="card-title mb-3"><?= Html::encode($this->title) ?></h3>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],

                    [
                        'attribute' => 'course',
                        'label' => 'Course Group Desc',
                        'value' => function($model) {
                            return $model['course_group_desc'];
                        }
                    ],
                    [
                        'attribute' => 'Course Group Name',

                        'value' => function($model) {
                            return $model['course_group_name'];
                        }

                    ],

                    [
                        'attribute' => 'Course Group Type',

                        'value' => function($model) {
                            return $model['course_group_type'];
                        }

                    ],

                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} ',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return  Html::a(' Update', ['/exam-management/prog-curr-course-group/update','course_group_id' => $model['course_group_id']], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                            },
                        ]

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
