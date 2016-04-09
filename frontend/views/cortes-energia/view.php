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

	<?php
	if ($model->estado==CortesEnergia::ESTADO_ACTIVO) {
		echo '<p>';
		if (\Yii::$app->user->can('modificarCorte')) {
			echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
		}
		if (\Yii::$app->user->can('borrarCorte')) {				
			echo ' '.Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',	]);
		}
		echo '</p>';
	}	
	?>

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
