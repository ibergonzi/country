<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */

$this->title = 'Eliminar novedad de generador';
$this->params['breadcrumbs'][] = ['label' => 'Cortes de energía', 'url' => ['cortes-energia/index']];
$this->params['breadcrumbs'][] = ['label' => 'Novedades de generadores', 'url' => ['cortes-energia-gen/index','idParent'=>$parent->id]];
$this->params['breadcrumbs'][] = ['label' => 'Novedad de generador', 'url' => ['cortes-energia-gen/view','id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personas-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'generador.descripcion',            
            'hora_desde:datetime',
			'hora_hasta:datetime',
        ],
    ]) ?>

    <?= $this->render('_delete', [
        'model' => $model,
    ]) ?>

</div>
