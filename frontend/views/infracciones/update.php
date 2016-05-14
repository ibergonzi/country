<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Infracciones */

$this->title = 'Update Infracciones: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="infracciones-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
