<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\MensVehiculos;

/* @var $this yii\web\View */
/* @var $model frontend\models\MensVehiculos */

$this->title = 'Detalle de mensaje sobre vehículo';
$this->params['breadcrumbs'][] = ['label' => 'Mensajes sobre Vehículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mens-vehiculos-view">

    <h3><?= Html::encode($this->title) ?></h3>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'avisar_a',
            'mensaje',
            'model_id',
            'patente',
            'created_by',
            'usuario_crea',
            'created_at:datetime',
            'updated_by',
            'usuario_borra',
            'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => MensVehiculos::getEstados($model->estado),
			],
        ],
    ]) ?>

</div>
