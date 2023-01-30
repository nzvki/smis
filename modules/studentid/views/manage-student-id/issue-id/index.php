<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\studentid\models\search\StudentIdSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?php
$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],
    'full_name',
    'registration_number',
    'prog_full_name',
    'printing_date:date',
    'issuance_date:date',
    'valid_from:date',
    'valid_to:date',
    'barcode',
    'id_status',
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{id-action}',
        'buttons' => [
            'id-action' => function ($url, $model) {

                if ($model['id_status'] === \app\modules\studentid\models\StudentIdStatus::ID_ACTIVE) {
                    return Html::a('Reprint ID', [
                        'print-id', 'id' => $model['student_id_serial_no']
                    ], [
                        'class' => 'btn btn-success w-100',
                        'title' => 'Reprint ID',
                    ]);
                }
                return Html::a('Issue', [
                    'issue-ready-id', 'id' => $model['student_id_serial_no']
                ], [
                    'class' => 'btn btn-info w-100',
                    'title' => 'Issue ID',
                ]);
            },
        ],
    ],
];
?>

<div class="row mt-5">
    <div class="col">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
            'columns' => $gridColumn,
            'pjax' => false,
            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-id']],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => $this->title,
            ],
            'export' => false,
            'toolbar' => [
                '{toggleData}',
            ],
            'itemLabelSingle' => 'Student ID',
            'itemLabelPlural' => 'Student IDs'
        ]); ?>
    </div>
</div>
