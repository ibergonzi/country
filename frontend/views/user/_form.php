<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

use yii\helpers\ArrayHelper;
use frontend\models\AuthAssignment;

use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class='container'>
	
	<div class='row'>

		<div class="col-md-9">
			
			<div class="user-form">
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'id',
						'username',
						],
					])            
				?>        
				<?php $form = ActiveForm::begin(
						// agregado a mano para hacer uploads
						['options' => ['enctype' => 'multipart/form-data']]        
				); ?>
				
				<div class="form-group">
				<?php 
				
					$rol=User::getRol($model->id);	

					echo Html::label('Rol', 'rol',['class'=>'control-label']);
					//Yii::trace(ArrayHelper::map(AuthAssignment::listaRoles(), 'name', 'description'));
					echo Html::dropDownList('rol', $rol->name,
							 ArrayHelper::map(AuthAssignment::listaRoles(), 'name', 'description')
							,['id'=>'rol','class'=>'form-control']);
				
				?>
				<?php
					if ($model->isNewRecord) echo $form->field($model, 'foto')->fileInput() ;
					if (!$model->isNewRecord) echo $form->field($model, 'foto')
													->hint($model->foto)
													->fileInput() ;
				?>
				</div>



				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>

			</div>
		</div>	
		<div class="col-md-3 pull-right">
		<?php
			if (!empty($model->foto)) {
				echo Html::img(Yii::$app->urlManager->createUrl('images/usuarios/'.$model->foto),
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
