<?php

use app\models\SmWithdrawalApproval;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalApprovalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sm Withdrawal Approvals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-withdrawal-approval-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sm Withdrawal Approval', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'withdrawal_approval_id',
            'withdrawal_request_id',
            'approver_id',
            'comments:ntext',
            'approval_status',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SmWithdrawalApproval $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'withdrawal_approval_id' => $model->withdrawal_approval_id, 'withdrawal_request_id' => $model->withdrawal_request_id, 'approver_id' => $model->approver_id, 'approval_status' => $model->approval_status]);
                 }
            ],
        ],
    ]); ?>


</div>
