<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergiaGen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cortes-energia-gen-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_cortes_energia')->textInput() ?>

    <?= $form->field($model, 'id_generador')->textInput() ?>

    <?= $form->field($model, 'hora_desde')->textInput() ?>

    <?= $form->field($model, 'hora_hasta')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <?= $form->field($model, 'motivo_baja')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
