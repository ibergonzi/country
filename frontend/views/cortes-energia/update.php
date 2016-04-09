<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergia */

$this->title = 'Modificación de corte de energía';
$this->params['breadcrumbs'][] = ['label' => 'Cortes de energía', 'url' => ['cortes-energia/index']];
$this->params['breadcrumbs'][] = ['label' => 'Corte de energía', 'url' => ['cortes-energia/view','id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cortes-energia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
