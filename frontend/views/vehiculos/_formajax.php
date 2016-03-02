<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\Vehiculos;
use yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $model frontend\models\Vehiculos */
/* @var $form yii\widgets\ActiveForm */

$js = 
<<<JS
	$('form#form-vehiculonuevo-ajax').
		on('beforeSubmit', function(e) {
			var form = $(this);
			$.post(
				form.attr('action'),
				form.serialize()
			).done(function(result) {
					var seleccion=$('#selectorVehiculos');
					var option = $('<option></option>').
						 attr('selected', true).
						 text(result.modelP['patente']+' '+result.modelP['marca']).
						 val(result.modelP['id']);
					option.appendTo(seleccion);
					seleccion.trigger('change');
					$('#modalvehiculonuevo').modal('hide');
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

			
			<div class="vehiculos-form">

				<?php $form = ActiveForm::begin(						
					[
						'id' => 'form-vehiculonuevo-ajax',
						// se habilita ajax para la validación porque patente tiene un rule "unique" (tiene que ir a la BD)
						'enableAjaxValidation'=>true,
					]  
					);  
				?>

				<?= $form->field($model, 'patente')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

				<?= $form->field($model, 'marca')->widget(AutoComplete::className(),[
						'model' => $model,
						'attribute' => 'marca',
						'options'=>[
							'style'=>'text-transform: uppercase',
							'class'=>'form-control',
							'max-height'=>'100px',
							'overflow-y'=>'auto',
							'overflow-x'=>'hidden',
							'z-index'=>'5000',

						],
						'clientOptions' => [
							'source' => Vehiculos::getMarcasVehiculos(),
							'minLength' => 1,
							'appendTo'=>'#form-vehiculonuevo-ajax',
						   
						], 
					])
				?>

				<?= $form->field($model, 'modelo')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

				<?= $form->field($model, 'color')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>
				
				<?= $form->field($model, 'estado')->hiddenInput()->label(false) ?>


				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>

			</div>
			
					
