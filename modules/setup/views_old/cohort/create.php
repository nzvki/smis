<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\generated\Cohort */

$this->title = 'Create Cohort';
$this->params['breadcrumbs'][] = ['label' => 'Setup', 'url' => ['/setup']];
$this->params['breadcrumbs'][] = ['label' => 'Cohorts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cohort-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
