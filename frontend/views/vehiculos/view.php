<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Vehiculos;

/* @var $this yii\web\View */
/* @var $model frontend\models\Vehiculos */

$this->title = 'Detalle de vehiculo';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vehiculos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehiculos-view">

    <h3><?= Html::encode($this->title) ?></h3>

	<?php 
	if ($model->estado==Vehiculos::ESTADO_ACTIVO) {
		if ($model->id !== \Yii::$app->params['sinVehiculo.id'] && $model->id !== \Yii::$app->params['bicicleta.id']) {
			echo '<p>';
			echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])
			
			.'  '. Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				]);
			echo '</p>';	
		}
	}
	?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'patente',
            'marca',
            'modelo',
            'color',
			'userCreatedBy.username',
			'created_at:datetime',
			'userUpdatedBy.username',
			'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => Vehiculos::getEstados($model->estado)
			],
			'motivo_baja',
        ],
    ]) ?>

</div>
