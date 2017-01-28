<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;


/* @var $this yii\web\View */
/* @var $model frontend\models\AutorizadosHorarios */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('$("#autorizadoshorarios-dia").focus()', yii\web\View::POS_READY);

?>

<div class="autorizados-horarios-form">

    <?php $form = ActiveForm::begin(); ?>

     <?= $form->field($model, 'dia')->dropDownList($model->dias) ?>

    <?= $form->field($model, 'hora_desde')->widget(DateControl::classname(), [
		'type'=>DateControl::FORMAT_TIME,
		'displayFormat'=>'php:H:i'
	]) ?>

    <?= $form->field($model, 'hora_hasta')->widget(DateControl::classname(), [
		'type'=>DateControl::FORMAT_TIME,
		'displayFormat'=>'php:H:i'
	]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Aceptar' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
