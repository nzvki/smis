<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SmNameChangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sm Name Changes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-name-change-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sm Name Change', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name_change_id',
            'request_date',
            'student_id',
            'new_surname',
            'new_othernames',
            //'reason',
            //'document_url:url',
            //'current_surname',
            //'current_othernames',
            //'status',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SmNameChange $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'name_change_id' => $model->name_change_id]);
                 }
            ],
        ],
    ]); ?>


</div>
