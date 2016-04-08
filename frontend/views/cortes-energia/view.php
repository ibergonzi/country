<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\CortesEnergia;

/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergia */

$this->title = 'Corte de energía';
$this->params['breadcrumbs'][] = ['label' => 'Cortes de energía', 'url' => ['cortes-energia/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cortes-energia-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
         ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'hora_desde:datetime',
			'hora_hasta:datetime',
			'userCreatedBy.username',
			'created_at:datetime',
			'userUpdatedBy.username',
			'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => CortesEnergia::getEstados($model->estado)
			],							
			//'estado',
			'motivo_baja',
        ],
    ]) ?>

</div>
