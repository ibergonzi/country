<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accesos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_persona')->textInput() ?>

    <?= $form->field($model, 'ing_id_vehiculo')->textInput() ?>

    <?= $form->field($model, 'ing_fecha')->textInput() ?>

    <?= $form->field($model, 'ing_hora')->textInput() ?>

    <?= $form->field($model, 'ing_id_porton')->textInput() ?>

    <?= $form->field($model, 'ing_id_user')->textInput() ?>

    <?= $form->field($model, 'egr_id_vehiculo')->textInput() ?>

    <?= $form->field($model, 'egr_fecha')->textInput() ?>

    <?= $form->field($model, 'egr_hora')->textInput() ?>

    <?= $form->field($model, 'egr_id_porton')->textInput() ?>

    <?= $form->field($model, 'egr_id_user')->textInput() ?>

    <?= $form->field($model, 'id_concepto')->textInput() ?>

    <?= $form->field($model, 'motivo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cant_acomp')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <?= $form->field($model, 'motivo_baja')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
