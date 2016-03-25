<?php

use kartik\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;



/* @var $this yii\web\View */
/* @var $model frontend\models\PersonaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="persona-search">

    <?php $form = ActiveForm::begin([
		//'id'=>'formfec1',
        'action' => ['index'],
        'method' => 'get',
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
    
        <?php
			if (\Yii::$app->session->get('accesosFecDesdeF')) {			
				echo Html::a('<span class="glyphicon glyphicon-remove"></span>',['index'],[
										'class'=>'btn btn-danger',
										'title'=>'Eliminar Filtro',
										'data'=>[
											'params'=>['resetFechas'=>true],
											'method'=>'get'
												]
									]);        
			}

		?>
    </div>



</div>
