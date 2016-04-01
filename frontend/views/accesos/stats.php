<?php

use kartik\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;

use kartik\grid\GridView;

$this->title='Estadistica de accesos';

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

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

			echo GridView::widget([
				'dataProvider' => $dataProvider,
				'options'=>['id'=>'gridStats'],
				'condensed'=>true,
				'showPageSummary'=>true,				
				'columns'=>[
					[
					'attribute'=>'dia',
					'group'=>true,
					'groupFooter'=>function ($model, $key, $index, $widget) {
						return [
							'mergeColumns'=>[[0,1]], // columns to merge in summary
							'content'=>[             // content to show in each summary cell
								0=>'Subtotal (' . $model['dia'] . ')',
								2=>GridView::F_SUM,
								3=>GridView::F_SUM,

							],
							'contentFormats'=>[      // content reformatting for each summary cell
								2=>['format'=>'number', 'decimals'=>0],
								3=>['format'=>'number', 'decimals'=>2],

							],
							'contentOptions'=>[      // content html attributes for each summary cell
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
					//'contentOptions'=>['style'=>'text-align:right;'],
					//'value'=>function ($model, $key, $index, $column) { Yii::trace($model);return number_format($model['porc'],2);},
					'hAlign'=>'right',
					'format'=>['decimal', 2],	
					'pageSummary'=>true				
					],
				],	
			]);
		}
	?>
	</div>




</div>
