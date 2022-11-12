<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SmNameChangeApprovalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sm Name Change Approvals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-name-change-approval-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sm Name Change Approval', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name_change_approval_id',
            'name_change_id',
            'approval_status',
            'remarks',
            'approved_by',
            //'approval_date',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SmNameChangeApproval $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'name_change_approval_id' => $model->name_change_approval_id]);
                 }
            ],
        ],
    ]); ?>


</div>
