<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\date\DatePicker;
use kartik\datecontrol\DateControl

/* @var $this yii\web\View */
/* @var $model frontend\models\Persona */
/* @var $form yii\widgets\ActiveForm $("#persona-fecnac-disp").val("");*/
?>
<div class='container'>
	
	<div class='row'>

		<div class="col-md-9">
	
			<div class="persona-form">

				<?php $form = ActiveForm::begin(
						// agregado a mano para hacer uploads
						['options' => ['enctype' => 'multipart/form-data']]    
				); ?>

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
						) 
				?>
				<?php
				if ($model->isNewRecord) echo $form->field($model, 'foto')->fileInput() ;
				if (!$model->isNewRecord) echo $form->field($model, 'foto')
												->hint($model->foto)
												->fileInput() ;
				?>

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
