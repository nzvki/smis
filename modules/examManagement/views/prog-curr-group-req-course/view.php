<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
// @var $model app\models\ProgCurrLevelRequirement

$this->title = $model->prog_curr_group_req_course_id;
$this->params['breadcrumbs'][] = ['label' => 'Prog Curr Required Course', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="org-course-prerequisite-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prog_curr_group_req_course_id' => $model->prog_curr_group_req_course_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prog_curr_group_req_course_id' => $model->prog_curr_group_req_course_id], [
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
            'prog_curr_group_req_course_id',
            'prog_curr_group_requirement_id',
            'prog_curriculum_course_id',
            'credit_factor',
        ],
    ]) ?>

</div>
