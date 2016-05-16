<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */
/* @var $form yii\widgets\ActiveForm */
?>
<div class='container'>
	
	<div class='row'>

		<div class="col-md-9">
			
			<div class="personas-form">

				<?php $form = ActiveForm::begin(						
					// agregado a mano para hacer uploads
						['options' => ['enctype' => 'multipart/form-data']] ); ?>

				<?= $form->field($model, 'apellido')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

				<?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

				<?= $form->field($model, 'nombre2')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

				<?= $form->field($model, 'id_tipo_doc')->dropDownList($model->listaTiposdoc) ?>

				<?= $form->field($model, 'nro_doc')->textInput(['maxlength' => true]) ?>

				<?php
				if ($model->isNewRecord) echo $form->field($model, 'foto')->fileInput() ;
				if (!$model->isNewRecord) echo $form->field($model, 'foto')
												->hint($model->foto)
												->fileInput() ;
				?>

				<?= $form->field($model, 'estado')->hiddenInput()->label(false) ?>

				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>

			</div>
			
		</div>	
		
		<div class="col-md-3">

				<?php
				$sinImg=Yii::$app->urlManager->createUrl('images/sinfoto.png');				
				if (!empty($model->foto)) {
					$imgFile=Yii::$app->urlManager->createUrl('images/personas/'.$model->foto);
					echo Html::img($imgFile,['class'=>'img-thumbnail pull-right','onerror'=>"this.src='$sinImg'"]);
				} else {
					echo Html::img($sinImg,	['class'=>'img-thumbnail pull-right']);
				}
				?>

		</div>	
	</div>
</div>					
