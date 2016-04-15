<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidadPersonas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uf-titularidad-personas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uf_titularidad_id')->textInput() ?>

    <?= $form->field($model, 'id_persona')->textInput() ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observaciones')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
