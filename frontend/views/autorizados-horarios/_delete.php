<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('$("#autorizadoshorarios-motivo_baja").focus();',yii\web\View::POS_READY);

?>
			
			<div class="personas-form">
				
				<?php $form = ActiveForm::begin(); ?>

				<?= $form->field($model, 'motivo_baja')->textInput(['maxlength' => true]) ?>

				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>

			</div>
			
		
