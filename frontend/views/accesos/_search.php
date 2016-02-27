<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accesos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_persona') ?>

    <?= $form->field($model, 'ing_id_vehiculo') ?>

    <?= $form->field($model, 'ing_fecha') ?>

    <?= $form->field($model, 'ing_hora') ?>

    <?php // echo $form->field($model, 'ing_id_porton') ?>

    <?php // echo $form->field($model, 'ing_id_user') ?>

    <?php // echo $form->field($model, 'egr_id_vehiculo') ?>

    <?php // echo $form->field($model, 'egr_fecha') ?>

    <?php // echo $form->field($model, 'egr_hora') ?>

    <?php // echo $form->field($model, 'egr_id_porton') ?>

    <?php // echo $form->field($model, 'egr_id_user') ?>

    <?php // echo $form->field($model, 'id_concepto') ?>

    <?php // echo $form->field($model, 'motivo') ?>

    <?php // echo $form->field($model, 'cant_acomp') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'motivo_baja') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
