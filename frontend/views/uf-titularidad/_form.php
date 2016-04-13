<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uf-titularidad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_uf')->textInput() ?>

    <?= $form->field($model, 'tipo_movim')->textInput() ?>

    <?= $form->field($model, 'fec_desde')->textInput() ?>

    <?= $form->field($model, 'fec_hasta')->textInput() ?>

    <?= $form->field($model, 'exp_telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'exp_direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'exp_localidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'exp_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <?= $form->field($model, 'motivo_baja')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ultima')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
