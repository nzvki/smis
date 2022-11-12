<?php

use app\models\generated\AdmittedStudent;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\generated\search\AdmittedStudentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Admitted Students';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admitted-student-index">

    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Create Admitted Student', ['create'], ['class' => 'btn btn-success float-end']) ?>
    </h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'adm_refno',
            'kcse_index_no',
            'kcse_year',
//            'student_name',
            'primary_phone_no',
            'alternative_phone_no',
            'primary_email:email',
            'alternative_email:email',
            'post_code',
            'post_address',
            'town',
            'kuccps_prog_code',
            //'uon_prog_code',
            //'national_id',
            //'birth_cert_no',
            'source_id',
            //'passport_no',
            //'admission_status',
            //'application_refno',
            'intake_code',
            //'student_category_id',
//            [
//                'class' => ActionColumn::class,
//                'urlCreator' => function ($action, AdmittedStudent $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'adm_refno' => $model->adm_refno]);
//                 }
//            ],
        ],
    ]); ?>


</div>
