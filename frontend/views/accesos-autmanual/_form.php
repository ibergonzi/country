<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\datecontrol\DateControl;

use frontend\models\AccesosAutmanual;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosAutmanual */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accesos-autmanual-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'hora_desde')->widget(DateControl::classname(), [
		'type'=>DateControl::FORMAT_DATETIME,
		'displayFormat'=>'php:d/m/Y H:i'
	]) ?>

    <?= $form->field($model, 'hora_hasta')->widget(DateControl::classname(), [
		'type'=>DateControl::FORMAT_DATETIME,
		'displayFormat'=>'php:d/m/Y H:i'		
	]) ?>

    <?= $form->field($model, 'estado')->dropDownList(AccesosAutmanual::getEstados()) ?>


    <div class="form-group">
        <?= Html::submitButton('Aceptar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
