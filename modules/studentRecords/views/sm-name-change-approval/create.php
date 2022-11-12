<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SmNameChangeApproval */

$this->title = 'Create Sm Name Change Approval';
$this->params['breadcrumbs'][] = ['label' => 'Sm Name Change Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sm-name-change-approval-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
