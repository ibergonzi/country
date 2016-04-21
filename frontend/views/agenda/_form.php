<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Agenda */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('$("#agenda-nombre").focus()', yii\web\View::POS_READY);
?>

<div class="agenda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'localidad')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'cod_pos')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'provincia')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'pais')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'telefono1')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'telefono2')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'fax')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'palabra')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'actividad')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
