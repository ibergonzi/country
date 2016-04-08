<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Generadores;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergiaGen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cortes-energia-gen-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_generador')->dropDownList(Generadores::getGeneradoresActivos()) ?>

    <?= $form->field($model, 'hora_desde')->widget(DateControl::classname(), [
		'type'=>DateControl::FORMAT_DATETIME,
		'displayFormat'=>'php:d/m/Y H:i'
	]) ?>

    <?= $form->field($model, 'hora_hasta')->widget(DateControl::classname(), [
		'type'=>DateControl::FORMAT_DATETIME,
		'displayFormat'=>'php:d/m/Y H:i'		
	]) ?>

    <?= $form->field($model, 'observaciones')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Aceptar' : 'Aceptar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
