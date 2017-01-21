<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\Autorizados;
use frontend\models\AutorizadosHorarios;

use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;
use kartik\grid\GridView;


// esto es para que las columnas del detailView no cambien de tamaño
$this->registerCss('table.detail-view th {width: 25%;} table.detail-view td {width: 75%;}');	

// Achica los gridview
$this->registerCss('
.panel-heading {
  padding: 0px 5px;
  border-bottom: 1px solid transparent;
  border-top-left-radius: 2px;
  border-top-right-radius: 2px;
}
.table-condensed > thead > tr > th,
.table-condensed > tbody > tr > th,
.table-condensed > tfoot > tr > th,
.table-condensed > thead > tr > td,
.table-condensed > tbody > tr > td,
.table-condensed > tfoot > tr > td {
  padding: 1px;
}
');
?>

<div class="comentarios-form">


    <?php 
    	$models=Autorizados::find()->where(['id_persona'=>$idPersona,'estado'=>1])
			->andWhere(['or',['fec_hasta'=>null],'fec_hasta>=now()'])
			->orderBy(['id_autorizante'=>SORT_ASC,'fec_desde'=>SORT_DESC])->all();
		$primeraVez=true;	
		foreach($models as $m) {
			if ($primeraVez) {
				echo '<p>Cantidad de autorizaciones '.kartik\helpers\Html::badge(count($models)).'</p>';
				$primeraVez=false;
			}
			if (empty($m->fec_desde)) {
				$co=[
					'autorizante.apellido',
					'autorizante.nombre',
					[
						'label'=>'Tipo autorización',
						'value'=>'PERMANENTE'
					],
				];
			} else {
				$co=[
				'autorizante.apellido',
				'autorizante.nombre',
				'fec_desde:date',
				'fec_hasta:date',
				];
			}
			echo DetailView::widget([
			'model' => $m,
			'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],
			'attributes' => $co,
			]);
			
			
			
			$dp=AutorizadosHorarios::find()
				->where(['id_autorizado'=>$m->id,'estado'=>1])
				->orderBy(['dia'=>SORT_ASC])->all();
			if (count($dp) > 0) {
				$dataProvider = new ArrayDataProvider(['allModels'=>$dp]);
				echo GridView::widget([
				'dataProvider'=> $dataProvider,
				'layout'=>'{items}',
				'condensed'=>true,
				'columns'=>[
					[
						'attribute'=>'dia',
						'value' => function ($model, $index, $widget) {
							$semana=['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
							return $semana[$model->dia];
						}
					],
					'hora_desde:time',
					'hora_hasta:time',
					],
				]);
			}

			echo '<hr/>';
		}
    ?>

</div>
