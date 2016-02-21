<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */
/* @var $form yii\widgets\ActiveForm */
?>
<div class='container'>
	
	<div class='row'>

		<div class="col-md-9">
			
			<div class="personas-form">
				
					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'apellido',
							'nombre',
							'nombre2',
							'tipoDoc.desc_tipo_doc_abr',
							'nro_doc',

						],
					]) ?>				
				

				<?php $form = ActiveForm::begin(); ?>

				<?= $form->field($model, 'motivo_baja')->textInput(['maxlength' => true]) ?>

				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>

			</div>
			
		</div>	
		
		<div class="col-md-3">

			<?php
				if (!empty($model->foto)) {
					echo Html::img(Yii::$app->urlManager->createUrl('images/personas/'.$model->foto),
							['class'=>'img-thumbnail pull-right']);
				}
				else
				{
					echo Html::img(Yii::$app->urlManager->createUrl('images/sinfoto.png'),
						['class'=>'img-thumbnail pull-right']);
				}
			?>

		</div>	
	</div>
</div>					
