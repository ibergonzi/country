<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */
/* @var $form yii\widgets\ActiveForm */

// la variable $selector se define desde el form que llama personas/create-ajax (esto se define asi para cuando desde el mismo form
// se usa mas de un select2 de personas)
$js = 
<<<JS
	$('form#form-personanueva-ajax').
		on('beforeSubmit', function(e) {
			var form = $(this);
			$.post(
				form.attr('action'),
				form.serialize()
			).done(function(result) {
					var seleccion=$('#$selector');
					var option = $('<option></option>').
						 attr('selected', true).
						 text(result.modelP['apellido']+' '+result.modelP['nombre']+' D:'+result.modelP['nro_doc']+' ('+result.modelP['id']+')').
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

			
			<div class="personas-form">

				<?php $form = ActiveForm::begin(						
					[
						'id' => 'form-personanueva-ajax',
						// se habilita ajax para la validación porque el nro_doc tiene un rule "unique" (tiene que ir a la BD)
						'enableAjaxValidation'=>true,
					]  
					);  
				?>

				<?= $form->field($model, 'apellido')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

				<?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

				<?= $form->field($model, 'nombre2')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

				<?= $form->field($model, 'id_tipo_doc')->dropDownList($model->listaTiposdoc) ?>

				<?= $form->field($model, 'nro_doc')->textInput(['maxlength' => true]) ?>

				<?= $form->field($model, 'estado')->hiddenInput()->label(false) ?>

				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>

			</div>
			
					
