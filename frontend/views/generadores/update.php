<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Generadores */

$this->title = 'Update Generadores: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Generadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="generadores-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
