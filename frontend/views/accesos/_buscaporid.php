<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */
/* @var $form yii\widgets\ActiveForm */


?>

			
<div class="personas-form">

	<?php $form = ActiveForm::begin(
					[
						'id' => 'form-buscaporid',
					]  	
	); ?>

	<?= $form->field($model, 'id')->textInput() ?>


	<div class="form-group">
		<?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
			
	
