<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\Uf;
use frontend\models\UfTitularidad;
use frontend\models\UfTitularidadPersonas;
use yii\data\ActiveDataProvider;



use kartik\datecontrol\DateControl;

use kartik\grid\GridView;

?>

<div class="uf-titularidad-form">



			<?php $form = ActiveForm::begin(); ?>

			<?php //$form->field($model, 'id_uf')->textInput() ?>

			<div class='row'>
				<div class='col-md-6'>
					<?= $form->field($model, 'tipo_movim')->dropDownList($model->listaMovimientos,['disabled' => true]) ?>
				</div>			
			
				<div class='col-md-3'>
					<?= $form->field($model, 'fec_desde')->widget(DateControl::classname(), [
						'type'=>DateControl::FORMAT_DATE,
					]) ?>
				</div>
				<div class='col-md-3'>
					<?= $form->field($model, 'fec_hasta')->widget(DateControl::classname(), [
						'type'=>DateControl::FORMAT_DATE,
					]) ?>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-3'>
					<?= $form->field($model, 'exp_telefono')->textInput(['maxlength' => true]) ?>
				</div>
				<div class='col-md-3'>
					<?= $form->field($model, 'exp_direccion')->textInput(['maxlength' => true]) ?>
				</div>
				<div class='col-md-3'>
					<?= $form->field($model, 'exp_localidad')->textInput(['maxlength' => true]) ?>
				</div>			
				<div class='col-md-3'>
					<?= $form->field($model, 'exp_email')->textInput(['maxlength' => true]) ?>
				</div>	
			</div>		
			



			<div class='row'>
				<div class='col-md-3'>
					<p>
						<?= Html::submitButton('Aceptar' , ['class' => 'btn btn-primary',
						 //'style'=>'vertical-align:text-bottom;'
						]) ?>
					</p>					
				</div>	
				<?php ActiveForm::end(); ?>				
				<div class='col-md-9'>

				</div>

			</div>
			

</div>
