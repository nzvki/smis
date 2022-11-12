<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrgProgCurrUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Org Prog Curr Units';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="org-prog-curr-unit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Org Prog Curr Unit', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'prog_curriculum_unit_id',
            'org_unit_history_id',
            'prog_curriculum_id',
            'start_date',
            'end_date',
            //'status',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, OrgProgCurrUnit $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'prog_curriculum_unit_id' => $model->prog_curriculum_unit_id]);
                 }
            ],
        ],
    ]); ?>


</div>
