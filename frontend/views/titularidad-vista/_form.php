<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\TitularidadVista */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="titularidad-vista-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'id_titularidad')->textInput() ?>

    <?= $form->field($model, 'id_uf')->textInput() ?>

    <?= $form->field($model, 'desc_movim_uf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fec_desde')->textInput() ?>

    <?= $form->field($model, 'fec_hasta')->textInput() ?>

    <?= $form->field($model, 'exp_telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'exp_direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'exp_localidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'exp_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_persona')->textInput() ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc_tipo_doc_abr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nro_doc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'superficie')->textInput() ?>

    <?= $form->field($model, 'coeficiente')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
