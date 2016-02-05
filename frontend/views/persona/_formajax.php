<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model frontend\models\Persona */
/* @var $form yii\widgets\ActiveForm $("#persona-fecnac-disp").val("");*/

//$this->registerJs('$(document).on("beforeSubmit", "#form-personanueva-ajax", function () {return false;});',yii\web\View::POS_READY);
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
				form.parent().html(result.modelP);
				
				var seleccion=$("#selectorPersonas option:selected");
				seleccion.replaceWith("<option value='11' selected>CUAC</option>")
				//alert(result.modelP);

				
				$('#modalpersonanueva').modal('hide');
				location.reload();
			});
		
			return false;

			}).
		on('submit', function(e){
			e.preventDefault();
		});
JS;
$this->registerJs($js,yii\web\View::POS_READY);
?>

?>

<div class="persona-form">

    <?php $form = ActiveForm::begin(
		[
			'id' => 'form-personanueva-ajax',
			//'action' => \yii\helpers\Url::to(['persona/create-ajax']), // actionCreateAjax
			//'enableAjaxValidation' => true,
			//'validationUrl' => \yii\helpers\Url::to(['persona/validate-ajax']), //actionValidateAjax
		]    
    ); 
    ?>

    <?= $form->field($model, 'dni')->textInput() ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre2')->textInput(['maxlength' => true]) ?>
    
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
