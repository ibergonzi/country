<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Vehiculos;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model frontend\models\Vehiculos */

$this->title = 'Detalle de vehiculo';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vehiculos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehiculos-view">

    <h3><?= Html::encode($this->title) ?></h3>

	<?php 
	echo '<p>';
	if ($model->estado==Vehiculos::ESTADO_ACTIVO) {
		if ($model->id !== \Yii::$app->params['sinVehiculo.id'] && 
		    $model->id !== \Yii::$app->params['bicicleta.id'] && 
		    $model->id !== \Yii::$app->params['generico.id']) {
				if (\Yii::$app->user->can('altaModificarVehiculo')) {
					echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
				}
				if (\Yii::$app->user->can('borrarVehiculo')) {				
					echo ' '.Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',	]);
				}
	
		}
	}
	if (\Yii::$app->user->can('accederConsAccesos')) {	
		if (isset($model->ultIngreso->id)) {
			echo ' '.Html::a('Ult.Ingreso', ['accesos/view', 
				'id' => $model->ultIngreso->id], 
				['class' => 'btn btn-default','title'=>'Ver último ingreso']);
		}	
	}
	if ($model->id !== \Yii::$app->params['sinVehiculo.id'] && 
		$model->id !== \Yii::$app->params['bicicleta.id'] && 
		$model->id !== \Yii::$app->params['generico.id']) {	
		echo ' '.Html::a('Personas',['lista-personas','id_vehiculo'=>$model->id],[
			'class'=>'btn btn-default',
			'title'=>'Personas que utilizaron el vehiculo',
			'onclick'=>'$.ajax({
				type     :"POST",
				cache    : false,
				url  : $(this).attr("href"),
				success  : function(response) {
							if (response=="notFound") {return false;}
							$("#divpersonas").html(response);
							$("#modalpersonas").modal("show");
							}
			});
			return false;'							
		]
		);		
	}		
	echo '</p>';	
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
	<?php
	Modal::begin(['id'=>'modalpersonas',
		'header'=>'<span class="btn-warning">&nbsp;Personas que usaron el vehiculo&nbsp;</span>',
		]);
		echo '<div id="divpersonas"></div>';
	Modal::end();	
	?>
</div>
