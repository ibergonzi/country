<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('$("#infracciones-motivo_baja").focus();',yii\web\View::POS_READY);

?>

			
<div class="personas-form">
	
		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'id',
				'fecha:date',
				'hora:time',											
				'id_uf',
				'concepto.concepto',
				'multa_total',						

			],
		]) ?>				
	

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'motivo_baja')->textInput(['maxlength' => true]) ?>

	<div class="form-group">
		<?= Html::submitButton('Eliminar', ['class' => 'btn btn-danger']) ?>
	</div>

	<?php ActiveForm::end(); ?>


</div>					
