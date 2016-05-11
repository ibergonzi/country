<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\InfracConceptosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="infrac-conceptos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'concepto') ?>

    <?= $form->field($model, 'es_multa') ?>

    <?= $form->field($model, 'dias_verif') ?>

    <?= $form->field($model, 'multa_unidad') ?>

    <?php // echo $form->field($model, 'multa_precio') ?>

    <?php // echo $form->field($model, 'multa_reincidencia') ?>

    <?php // echo $form->field($model, 'multa_reinc_porc') ?>

    <?php // echo $form->field($model, 'multa_reinc_dias') ?>

    <?php // echo $form->field($model, 'multa_personas') ?>

    <?php // echo $form->field($model, 'multa_personas_precio') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'motivo_baja') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
