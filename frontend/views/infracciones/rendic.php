<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use frontend\models\Infracciones;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\InfraccionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cobro de multas';

$this->params['breadcrumbs'][] = ['label' => 'Emisión de informes de multas', 'url' => ['rendic-fechas']];
$this->params['breadcrumbs'][] = ['label' => 'Selección', 'url' => ['rendic-selec','fd'=>$fd,'fh'=>$fh]];
$this->params['breadcrumbs'][] = $this->title;


$this->registerCss('
.kv-grid-loading {
    opacity: 0.5;
    background: #ffffff url("../images/loading.gif") top center no-repeat !important;
}
');
?>
<div class="infracciones-index">

    <?php 
			    
		// Informe para el cobro de multas-------------------------------------------------------------	   

		$columns=[
			'id_uf',
            'id',
            /*
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
            */
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
				'attribute'=>'id_persona',
				'value'=>function ($model, $index, $widget) {
					return $model->persona->apellido.' '.$model->persona->nombre;
				},
            ],              
			'fecha:date',
			'hora:time',
            //'nro_acta',
            'concepto.concepto',
            'lugar',    
            [
				'attribute'=>'id_informante',
				'value'=>function ($model, $index, $widget) {
					return $model->informante->apellido.' '.$model->informante->nombre;
				},
            ],                   
            //'descripcion',
            //'notificado',
            //'fecha_verif',
            //'verificado',
            //'foto',
            'multaUnidad.unidad',
            // 'multa_monto',
            // 'multa_pers_cant',
            // 'multa_pers_monto',
            // 'multa_pers_total',
            [
				'attribute'=>'multa_total',
				'hAlign'=>'right',	
				'format'=>['decimal', 2],							
				'pageSummary'=>true
            ],

            [
				'attribute'=>'foto',
				'value'=>function ($model, $index, $widget) {
					return (empty($model->foto))?'No':'Si';
				},
            ]
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            //'estado',
            // 'motivo_baja',


        ];
        
		$toolbar=['{export}'];
		$lbl1='Informe para el cobro de multas';
		$lbl2='('.Yii::$app->formatter->asDate($fd).' - '.Yii::$app->formatter->asDate($fh).')';
		$pdfHeader=[
					'L'=>['content'=>Html::img(Yii::$app->homeUrl.'images/logoreportes.png')],
					'C'=>['content'=>$lbl1 . ' '. $lbl2,
						  //'font-size' => 80,
						  'font-style'=>'B'
						  //'color'=> '#333333'
						],
					'R'=>['content'=>\Yii::$app->params['lblName']],
			];
		$pdfFooter=[
			'L'=>['content'=>\Yii::$app->params['lblName2']],
			'C'=>['content'=>'página {PAGENO} de {nb}'],
			'R'=>['content'=>'Fecha:{DATE d/m/Y}'],
			];	
        
		echo "<h3>$lbl1 $lbl2</h3>"; 
		echo GridView::widget([
			'dataProvider' => $dataProvider,
			
			'options'=>['id'=>'gridInfracciones'],
			'columns' => $columns,
			'condensed'=>true, 
			'showPageSummary'=>true,	
			
			'layout'=>'{toolbar}{items}',
			
			'export' => [
				'label' => 'Exportar',
				'fontAwesome' => true,
				'showConfirmAlert'=>false,	
				'target'=>GridView::TARGET_BLANK,	
				
				//'target'=>GridView::TARGET_SELF,	
						
			],
			
			'toolbar' => $toolbar,
			
			'exportConfig' => [
				GridView::PDF => [
						'filename' => $lbl1,
						'config' => [
								'destination' => 'D',
								'methods' => [
									'SetHeader' => [
										['odd' => $pdfHeader, 'even' => $pdfHeader]
									],
									'SetFooter' => [
										['odd' => $pdfFooter, 'even' => $pdfFooter]
									],
								],
								
								'options' => [
									'title' => $lbl1,
									'subject' => '',
									'keywords' => '',
								],
								'contentBefore'=>'',
								'contentAfter'=>''
						]			
				],
				GridView::EXCEL => [
						'filename' => $lbl1,
						'config' => [
							'worksheet' => $lbl1,
							'cssFile' => ''
								]
				],
				GridView::CSV => [
						'filename' => $lbl1,
						'config' => [
							'colDelimiter' => ";",
							'rowDelimiter' => "\r\n",
						]
				],
			],						       
		]); 
		
		
		// Detalle de reincidencias para expensas-------------------------------------------------------------

		$columnsR=[
			[
				'attribute'=>'concepto.concepto',
				'group'=>true,
			],
			'id_uf',
			'cant',
			[
				'attribute'=>'tot',
				'hAlign'=>'right',	
				'format'=>['decimal', 2],					
			],
			[
				//'attribute'=>'multa_fec_reinc',
				'label'=>'Cant.Reincidencias',
				'value'=>function($m) use ($fd) {
					// cuenta todas las multas para la unidad funcional desde la fecha de reinc hasta el dia anterior al periodo informado
					$fh=date('Y-m-d', mktime(0, 0, 0, date('m',strtotime($fd)), 0, date('Y',strtotime($fd))));
					
					$cantMultas=Infracciones::find()->where([
							'id_uf'=>$m->id_uf,
							'estado'=>Infracciones::ESTADO_ACTIVO,
							'id_concepto'=>$m->id_concepto
							])
							->andWhere(['between','fecha',$m->multa_fec_reinc,$fh])->count();		
					if ($cantMultas > 1) {$cantMultas=$cantMultas-1;}
					$gfd=Yii::$app->formatter->asDate($m->multa_fec_reinc);
					$gfh=Yii::$app->formatter->asDate($fh);
										
					return "desde $gfd hasta $gfh: $cantMultas";
				}
			],

		];
		
		$toolbar=['{export}'];
		$lbl1='Detalle de reincidencias';
		$lbl2='('.Yii::$app->formatter->asDate($fd).' - '.Yii::$app->formatter->asDate($fh).')';
		$pdfHeader=[
					'L'=>['content'=>Html::img(Yii::$app->homeUrl.'images/logoreportes.png')],
					'C'=>['content'=>$lbl1 . ' '. $lbl2,
						  //'font-size' => 80,
						  'font-style'=>'B'
						  //'color'=> '#333333'
						],
					'R'=>['content'=>\Yii::$app->params['lblName']],
			];
		$pdfFooter=[
			'L'=>['content'=>\Yii::$app->params['lblName2']],
			'C'=>['content'=>'página {PAGENO} de {nb}'],
			'R'=>['content'=>'Fecha:{DATE d/m/Y}'],
			];		
			
		echo "<h3>$lbl1 $lbl2</h3>"; 
						
		echo GridView::widget([
			'dataProvider' => $dataProviderR,
					
			'options'=>['id'=>'gridReinc'],
			'columns' => $columnsR,
			'condensed'=>true, 
			//'showPageSummary'=>true,	
			
			'layout'=>'{toolbar}{items}',
			
			'export' => [
				'label' => 'Exportar',
				'fontAwesome' => true,
				'showConfirmAlert'=>false,	
				'target'=>GridView::TARGET_BLANK,	
				
				//'target'=>GridView::TARGET_SELF,	
						
			],
			
			'toolbar' => $toolbar,
			
			'exportConfig' => [
				GridView::PDF => [
						'filename' => $lbl1,
						'config' => [
								'destination' => 'D',
								'methods' => [
									'SetHeader' => [
										['odd' => $pdfHeader, 'even' => $pdfHeader]
									],
									'SetFooter' => [
										['odd' => $pdfFooter, 'even' => $pdfFooter]
									],
								],
								
								'options' => [
									'title' => $lbl1,
									'subject' => '',
									'keywords' => '',
								],
								'contentBefore'=>'',
								'contentAfter'=>''
						]			
				],
				GridView::EXCEL => [
						'filename' => $lbl1,
						'config' => [
							'worksheet' => $lbl1,
							'cssFile' => ''
								]
				],
				GridView::CSV => [
						'filename' => $lbl1,
						'config' => [
							'colDelimiter' => ";",
							'rowDelimiter' => "\r\n",
						]
				],
			],	       
		]); 		
    ?>

</div>
