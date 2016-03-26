<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\Accesos;
use frontend\models\AccesosAutorizantes;
use frontend\models\Comentarios;

/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

$this->title = 'Detalle de acceso';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accesos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// esto es para que las columnas del detailView no cambien de tamaño
$this->registerCss('table.detail-view th {width: 25%;} table.detail-view td {width: 75%;}');

?>
<div class="accesos-view">

    <h3><?= Html::encode($this->title) ?></h3>

   
	<?php
		if (!$pdf) {
			echo '<p>';
			if ($model->estado==Accesos::ESTADO_ACTIVO ) {
				if (\Yii::$app->user->can('borrarAcceso')) {
					echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
						'class' => 'btn btn-danger',
						]).'&nbsp;';
				}
			}
			echo Html::a('<i class="glyphicon glyphicon-print"></i> PDF', ['pdf', 'id' => $model->id], [
				'class' => 'btn btn-default',//'target'=>'_blank',
				]);					
			echo '</p>';	

		}			

	?>

    <?= DetailView::widget([
        'model' => $model,
		'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],        
        'attributes' => [
            'id',
			'ing_fecha:date',
			'ing_hora:time',
            [
				'attribute'=>'id_persona',
				'format' => 'raw',
				'value' =>Html::a($model->id_persona, 
								Yii::$app->urlManager->createUrl(
										['personas/view', 
										 'id' => $model->id_persona
										]),
										['title' => 'Ver detalle de persona',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										])
            ],
            'persona.apellido',
			'persona.nombre',  
			'persona.nombre2',
			'persona.tipoDoc.desc_tipo_doc_abr',
			'persona.nro_doc',          
            [
				'attribute'=>'ing_id_vehiculo',
				'format' => 'raw',
				'value' =>Html::a($model->ing_id_vehiculo, 
								Yii::$app->urlManager->createUrl(
										['vehiculos/view', 
										 'id' => $model->ing_id_vehiculo
										]),
										['title' => 'Ver detalle del vehiculo',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										])
            ],
            'ingVehiculo.patente',
            'ingVehiculo.marca',
            'ingVehiculo.modelo',
            'ingVehiculo.color',
            'ing_id_porton',
            'userIngreso.username',
            'accesosConcepto.concepto',
            'motivo',
            'egr_fecha:date',
			'egr_hora:time',
            [
				'attribute'=>'egr_id_vehiculo',
				'format' => 'raw',
				'value' =>Html::a($model->egr_id_vehiculo, 
								Yii::$app->urlManager->createUrl(
										['vehiculos/view', 
										 'id' => $model->egr_id_vehiculo
										]),
										['title' => 'Ver detalle del vehiculo',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										])
            ],
            'egrVehiculo.patente',
            'egrVehiculo.marca',
            'egrVehiculo.modelo',
            'egrVehiculo.color',
            'egr_id_porton',
            'userEgreso.username',
            'control',
            //'cant_acomp',
            'userCreatedBy.username',
            'created_at:datetime',
            'userUpdatedBy.username',
            'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => Accesos::getEstados($model->estado)
			],	
            'motivo_baja',
        ],
    ]) ?>
 
    <?php
    
		$aut=AccesosAutorizantes::findAll(['id_acceso'=>$model->id]);
		$primeraVez=true;			
		foreach ($aut as $a) {
			if ($primeraVez) {
				//echo '<hr/>';
				echo '<p>Cantidad de autorizantes/unidades '.kartik\helpers\Html::badge(count($aut)).'</p>';
				$primeraVez=false;
			}			
			echo DetailView::widget([
		        'model' => $a,
				'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],		        
				'attributes' => [
				    [
						'attribute'=>'id_persona',
						'format' => 'raw',
						'value' =>Html::a($a->id_persona, 
										Yii::$app->urlManager->createUrl(
												['personas/view', 
												 'id' => $a->id_persona
												]),
												['title' => 'Ver detalle de persona',
												 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
												 'target' => '_blank',
												 'data-pjax'=>'0'
												])
					],
					'persona.apellido',
					'persona.nombre',  
					'persona.nombre2',
					'id_uf'
					
				]
			]);
		}
    ?>
    <?php 
    	$models=Comentarios::find()->where(['model'=>'frontend\models\Accesos','model_id'=>$model->id])
			->orderBy(['created_at'=>SORT_DESC])->all();
		$primeraVez=true;	
		foreach($models as $m) {
			if ($primeraVez) {
				//echo '<hr/>';
				echo '<p>Cantidad de comentarios '.kartik\helpers\Html::badge(count($models)).'</p>';
				$primeraVez=false;
			}
			echo DetailView::widget([
			'model' => $m,
			'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],
			'attributes' => [
				//'id',
				'comentario',
				//'model',
				//'model_id',
				'userCreatedBy.username',
				'created_at:datetime',
				//'updated_by',
				//'updated_at',
				],
			]);
			echo '<hr/>';
		}
		
    ?>
</div>
