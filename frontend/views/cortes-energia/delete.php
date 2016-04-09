<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */

$this->title = 'Eliminar corte de energía';
$this->params['breadcrumbs'][] = ['label' => 'Cortes de energía', 'url' => ['cortes-energia/index']];
$this->params['breadcrumbs'][] = ['label' => 'Corte de energía', 'url' => ['cortes-energia/view','id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personas-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'hora_desde:datetime',
			'hora_hasta:datetime',
        ],
    ]) ?>

    <?= $this->render('_delete', [
        'model' => $model,
    ]) ?>

</div>
