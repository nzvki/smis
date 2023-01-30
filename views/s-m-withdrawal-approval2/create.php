<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalApproval $model */

$this->title = 'Create Sm Withdrawal Approval';
$this->params['breadcrumbs'][] = ['label' => 'Sm Withdrawal Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-withdrawal-approval-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
