<?php

use yii\helpers\Html;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\InfraccionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seleccione las multas para realizar la rendición';
$this->params['breadcrumbs'][] = $this->title;


$this->registerCss('
.kv-grid-loading {
    opacity: 0.5;
    background: #ffffff url("../images/loading.gif") top center no-repeat !important;
}
');
?>
<div class="infracciones-index">

    <h3><?= Html::encode($this->title.' ('.Yii::$app->formatter->asDate($fd).' - '.Yii::$app->formatter->asDate($fh).')') ?></h3>

    <?php 
			    
		$columns=[
			[
				'class' => '\kartik\grid\CheckboxColumn',
				'checkboxOptions' => function($model, $key, $index, $column) {
					 return ['checked' => true];
				 }
			],		
            'id',
            [
				'attribute'=>'id_uf',
				'format' => 'raw',
				'value' => function ($model, $index, $widget) {
					return Html::a($model->id_uf, 
								Yii::$app->urlManager->createUrl(
										['uf/view', 
										 'id' => $model->id_uf
										]),
										['title' => 'Ver detalle de la unidad funcional',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										]);
					},
            ],
            /*
            [
				'attribute'=>'id_vehiculo',
				'format' => 'raw',
				'value' => function ($model, $index, $widget) {
					return Html::a($model->id_vehiculo, 
								Yii::$app->urlManager->createUrl(
										['vehiculos/view', 
										 'id' => $model->id_vehiculo
										]),
										['title' => 'Ver detalle del vehiculo',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										]);
					},
            ],
            [
				'attribute'=>'id_persona',
				'format' => 'raw',
				'value' => function ($model, $index, $widget) {
					return Html::a($model->id_persona, 
								Yii::$app->urlManager->createUrl(
										['personas/view', 
										 'id' => $model->id_persona
										]),
										['title' => 'Ver detalle de persona',
										 // para que se abra el link en nueva pestaña hay que setear ademas pjax="0"
										 'target' => '_blank',
										 'data-pjax'=>'0'
										]);
					},
            ],   
            */         
            [
				'attribute'=>'fecha',
				'format'=>'date'
			],
            [
				'attribute'=>'hora',
				'format'=>'time'
			],
            'nro_acta',
            'lugar',
            'id_concepto',
            //'id_informante',
            'descripcion',
            //'notificado',
            //'fecha_verif',
            'verificado',
            //'foto',
            'multa_unidad',
            // 'multa_monto',
            // 'multa_pers_cant',
            // 'multa_pers_monto',
            // 'multa_pers_total',
            'multa_total',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            'estado',
            // 'motivo_baja',


        ];
        


		
    
    
		echo GridView::widget([
        'dataProvider' => $dataProvider,
		'pjax'=>true,
		'pjaxSettings'=>['neverTimeout'=>true,],  
		        
        'options'=>['id'=>'gridInfracciones'],
        'columns' => $columns,
		'condensed'=>true, 

	       
    ]); 
    ?>

</div>
