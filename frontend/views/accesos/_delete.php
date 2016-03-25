<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('$("#accesos-motivo_baja").focus();',yii\web\View::POS_READY);

?>

			
<div class="accesos-form">
	
		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'id',
				'persona.apellido',
				'persona.nombre',
				'persona.tipoDoc.desc_tipo_doc_abr',
				'persona.nro_doc',
				'ing_fecha:date',
				'ing_hora:time'

			],
		]) ?>				
	

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'motivo_baja')->textInput(['maxlength' => true]) ?>

	<div class="form-group">
		<?= Html::submitButton('Eliminar', ['class' => 'btn btn-danger']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
			
		
