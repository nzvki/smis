<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\studentid\models\StudentId */

$this->title = 'Report ID: ' . $model->barcode . '  as lost';
$this->params['breadcrumbs'][] = ['label' => 'Student Id', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Report lost id';
?>
<div class="student-id-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update-id-form', [
        'model' => $model,
    ]) ?>

</div>
