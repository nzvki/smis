<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\generated\Country */

$this->title = 'Update Country: ' . $model->NAME;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->NAME, 'url' => ['view', 'CODE' => $model->CODE]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="country-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
