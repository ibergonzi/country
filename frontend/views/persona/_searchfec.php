<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;

/* para usar con typeahed
use kartik\widgets\Typeahead;
use yii\helpers\Url;
*/

use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;
use app\models\Persona;


/* @var $this yii\web\View */
/* @var $model frontend\models\PersonaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="persona-search">




    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
		//'layout' => 'inline'        
    ]); ?>


<?php


$personaDesc = empty($model->id) ? '' : $model->apellido;
 
echo $form->field($model, 'id')->widget(Select2::classname(), [
    'initValueText' => $personaDesc, // set the initial display text
    'options' => ['placeholder' => '...'],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 3,
        'ajax' => [
            'url' => \yii\helpers\Url::to(['apellidoslist']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        'templateResult' => new JsExpression('function(id) { return id.text; }'),
        'templateSelection' => new JsExpression('function (id) { return id.text; }'),
    ],
]);


/*
echo $form->field($model, 'nomCompleto')->label(false)->widget(Typeahead::classname(),
//echo Typeahead::widget(
	[
		//'name' => 'nomCompleto',
		'options' => ['placeholder' => '...'],
		'scrollable' => true,
		'pluginOptions' => ['highlight'=>true],
		'dataset' => [
			[
		
				'display' => 'value',
			   'prefetch' => Url::to(['pre']),
			   'remote' =>
			   	[
					'url'=>Url::to(['persona/buscar']) . '?q=%QUERY',  
	 
					'wildcard' => '%QUERY',  
				]    
			]	
				
				//'limit' => 10
		]
    ]
);
*/
?>


    <?php
   
		echo $form->field($model, 'fecdesde')->widget(DateControl::className(),
						['type' =>DateControl::FORMAT_DATE,
						 
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
    </div>

    <?php ActiveForm::end(); ?>

</div>
