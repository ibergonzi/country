<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

use kartik\date\DatePicker;
use kartik\datecontrol\DateControl

/* @var $this yii\web\View */
/* @var $model frontend\models\Persona */
/* @var $form yii\widgets\ActiveForm $("#persona-fecnac-disp").val("");*/
?>

<div class="persona-form">

    <?php $form = ActiveForm::begin(); ?>

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
			) ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
