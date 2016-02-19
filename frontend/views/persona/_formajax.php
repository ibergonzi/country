<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model frontend\models\Persona */
/* @var $form yii\widgets\ActiveForm $("#persona-fecnac-disp").val("");*/

	$js = 
<<<JS
	$('form#form-personanueva-ajax').
		on('beforeSubmit', function(e) {
			var form = $(this);
			$.post(
				form.attr('action'),
				form.serialize()
			).done(function(result) {
				console.log(result);
				
			
				
					var seleccion=$('#selectorPersonas');
					var option = $('<option></option>').
						 attr('selected', true).
						 text(result.modelP['apellido']+' '+result.modelP['nombre']).
						 val(result.modelP['id']);
					option.appendTo(seleccion);
					seleccion.trigger('change');
					$('#modalpersonanueva').modal('hide');
					//location.reload();
				
			});
			return false;
			}).
		on('submit', function(e){
			e.preventDefault();
		});
JS;
$this->registerJs($js,yii\web\View::POS_READY);
?>



<div class="persona-form">

    <?php $form = ActiveForm::begin(
		[
			'id' => 'form-personanueva-ajax',
			// se habilita ajax para la validación porque el dni tiene un rule "unique" (tiene que ir a la BD)
			'enableAjaxValidation'=>true,
		]    
    ); 
    ?>

    <?= $form->field($model, 'dni')->textInput() ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'nombre2')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>
    
    <?= $form->field($model, 'fecnac')->widget(DateControl::className(),
						['type' =>DateControl::FORMAT_DATE,
						
						 'options'=>[
							 'id'=>'fcnc',
							 
							 'pluginEvents'=>[ 'clearDate'=>'function(e) { 
												$("#fcnc").val("");
											}'
								],	
							]	
						]
			) ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
