<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uf-titularidad-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_uf') ?>

    <?= $form->field($model, 'tipo_movim') ?>

    <?= $form->field($model, 'fec_desde') ?>

    <?= $form->field($model, 'fec_hasta') ?>

    <?php // echo $form->field($model, 'exp_telefono') ?>

    <?php // echo $form->field($model, 'exp_direccion') ?>

    <?php // echo $form->field($model, 'exp_localidad') ?>

    <?php // echo $form->field($model, 'exp_email') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'motivo_baja') ?>

    <?php // echo $form->field($model, 'ultima') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
