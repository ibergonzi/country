<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\Mensajes;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Comentarios */
/* @var $form yii\widgets\ActiveForm */

	$js = 
<<<JS
	$('form#form-mensajenuevo-ajax').
		on('beforeSubmit', function(e) {
			var form = $(this);
			$.post(
				form.attr('action'),
				form.serialize()
			).done(function(result) {
				$('#modalmensaje').modal('hide');
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

<div class="mensajes-form">

    <?php $form = ActiveForm::begin(
    		[
			'id' => 'form-mensajenuevo-ajax',
		]    
    ); ?>

    <?= $form->field($model, 'avisar_a')->textInput(['maxlength' => true,
		'readonly' => $model->isNewRecord ? false : true,
		'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'mensaje')->textArea(['maxlength' => true,
		'readonly' => $model->isNewRecord ? false : true,
		'style' => 'text-transform: uppercase']) ?>

	<?= $form->field($model, 'estado')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Aceptar' : 'Eliminar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php 

	if (!$model->isNewRecord) {
		echo DetailView::widget([
		'model' => $model,
		'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],
		'attributes' => [
			//'id',
			//'mensaje',
			//'model',
			//'model_id',
			'userCreatedBy.username',
			'created_at:datetime',
			//'updated_by',
			//'updated_at',
			],
		]);
	}
    ?>

</div>
