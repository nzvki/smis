<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
// @var $model app\models\ProgCurrLevelRequirement

$this->title = $model->course_group_id;
$this->params['breadcrumbs'][] = ['label' => 'Prog Course Group', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-course-prerequisite-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'course_group_id' => $model->course_group_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'course_group_id' => $model->course_group_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'course_group_id',
            'course_group_desc',
            'course_group_name',
            'course_group_type',
        ],
    ]) ?>

</div>
