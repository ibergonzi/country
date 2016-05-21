<?php

use kartik\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;

use kartik\grid\GridView;

use miloschuman\highcharts\Highcharts;

$this->title='Estadistica de accesos';

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonaSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<h4><?= Html::encode($this->title) ?></h4>
<div class="stats-search">

    <?php 
		
		$form = ActiveForm::begin([
		//'id'=>'formfec1',
        //'action' => ['stats'],
        //'method' => 'get',
		'layout' => 'inline'        
    ]); ?>

    <?php
		echo $form->field($model, 'fecdesde')->widget(DateControl::className(),
						['type' =>DateControl::FORMAT_DATE,
						 //'value'=>$model->fecdesde!=''?$model->fecdesde:'',
						 'options'=>[
							 'options'=>['placeholder'=>'Desde fecha'],							 
							 'id'=>'fcd',
							 'pluginEvents'=>[ 'clearDate'=>'function(e) { 
													$("#fcd").val("");												
											}'
								],	
							]
						]
	);

	?> 
    <?php
		echo $form->field($model, 'fechasta')->widget(DateControl::className(),
						['type' =>DateControl::FORMAT_DATE,
						 'options'=>[
						     'options'=>['placeholder'=>'Hasta fecha'],	
							 'id'=>'fch',
							 'pluginEvents'=>[ 'clearDate'=>'function(e) { 
													$("#fch").val("");												
											}'
								],	
							]						
						]
	);
	?> 

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php //Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    <?php ActiveForm::end(); ?>
    </div>
    <div>
	<?php
		if (!empty($dataProvider)) {
			
			$toolbar=['{export}'];
			$lbl2='';
			$pdfHeader=[
						'L'=>['content'=>\Yii::$app->params['lblName']],
						'C'=>['content'=>$this->title . $lbl2,
							  //'font-size' => 80,
							  'font-style'=>'B'
							  //'color'=> '#333333'
							],
						'R'=>['content'=>''],
					
				];
			$pdfFooter=[
				'L'=>['content'=>\Yii::$app->params['lblName2']],
				'C'=>['content'=>'página {PAGENO} de {nb}'],
				'R'=>['content'=>'Fecha:{DATE d/m/Y}'],
				];	
			echo '<br/>';			
			echo GridView::widget([
				'dataProvider' => $dataProvider,
				'options'=>['id'=>'gridStats'],
				'condensed'=>true,
				'showPageSummary'=>true,	
				
				'layout'=>'{toolbar}{items}{pager}',
				
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
							'filename' => $this->title,
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
										'title' => $this->title,
										'subject' => '',
										'keywords' => '',
									],
									'contentBefore'=>'',
									'contentAfter'=>''
							]			
					],
					GridView::EXCEL => [
							'filename' => $this->title,
							'config' => [
								'worksheet' => $this->title,
								'cssFile' => ''
									]
					],
					GridView::CSV => [
							'filename' => $this->title,
							'config' => [
								'colDelimiter' => ";",
								'rowDelimiter' => "\r\n",
							]
					],
				],						
							
				'columns'=>[
					[
					'attribute'=>'desc_dia',
					'group'=>true,
					'groupFooter'=>function ($model, $key, $index, $widget) {
						return [
							'mergeColumns'=>[[0,1]], 
							'content'=>[             
								0=>'Subtotal (' . $model['desc_dia'] . ')',
								2=>GridView::F_SUM,
								3=>GridView::F_SUM,

							],
							
							'contentFormats'=>[      
								//2=>['format'=>'number', 'decimals'=>0],
								//3=>['format'=>'number', 'decimals'=>2,'decPoint'=>',', 'thousandSep'=>'.'],

							],
							
							'contentOptions'=>[     
								0=>['style'=>'font-variant:small-caps;text-align:right'],
								2=>['style'=>'text-align:right'],
								3=>['style'=>'text-align:right'],
							],
							// html attributes for group summary row
							'options'=>['class'=>'danger','style'=>'font-weight:bold;']
						];
					}					
					],				
					'concepto',
					[
					'attribute'=>'cant',
					'hAlign'=>'right',
					'pageSummary'=>true					
					],
					[
					'attribute'=>'porc',
					'hAlign'=>'right',
					//'format'=>['decimal', 2],	
					'pageSummary'=>true				
					],
				],	
			]);

			echo '<div>';
			echo Highcharts::widget([
		   		'scripts' => [
						'modules/exporting',
				 ],
			    'options' => [
					  'title' => ['text' => 'Estadistica de accesos (cantidades por Concepto)'],
					  'xAxis' => [
						 'categories' => $categ,					 
					  ],
					  'yAxis' => [
						 'title' => ['text' => 'Accesos']
					  ],
					  'series'=>$series,
					  'lang' => [
							'printChart' => 'Imprimir gráfico',
							'downloadPNG' => 'Descargar imagen PNG',
							'downloadJPEG' => 'Descargar imagen JPEG',
							'downloadPDF' => 'Descargar documento PDF',
							'downloadSVG' => 'Descargar imagen vectorial SVG',
							'contextButtonTitle' => 'Acciones'
					  ]
			   ]			
			]);
			echo '</div><div>';
			echo Highcharts::widget([
		   		'scripts' => [
						'modules/exporting',
				 ],
			    'options' => [
					'plotOptions' => [
						'pie' => [
							'cursor' => 'pointer',
						],
					],
					'title' => ['text' => 'Estadistica de accesos (% por dia)'],
					'series' => [$pie],
					'lang' => [
							'printChart' => 'Imprimir gráfico',
							'downloadPNG' => 'Descargar imagen PNG',
							'downloadJPEG' => 'Descargar imagen JPEG',
							'downloadPDF' => 'Descargar documento PDF',
							'downloadSVG' => 'Descargar imagen vectorial SVG',
							'contextButtonTitle' => 'Acciones'
					 ]					 

			   ]			
			]);		
			echo '</div>';	
		}
	?>
	</div>




</div>
