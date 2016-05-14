<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\InfraccionesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="infracciones-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_uf') ?>

    <?= $form->field($model, 'id_vehiculo') ?>

    <?= $form->field($model, 'id_persona') ?>

    <?= $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'hora') ?>

    <?php // echo $form->field($model, 'nro_acta') ?>

    <?php // echo $form->field($model, 'lugar') ?>

    <?php // echo $form->field($model, 'id_concepto') ?>

    <?php // echo $form->field($model, 'id_informante') ?>

    <?php // echo $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'notificado') ?>

    <?php // echo $form->field($model, 'fecha_verif') ?>

    <?php // echo $form->field($model, 'verificado') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'multa_unidad') ?>

    <?php // echo $form->field($model, 'multa_monto') ?>

    <?php // echo $form->field($model, 'multa_pers_cant') ?>

    <?php // echo $form->field($model, 'multa_pers_monto') ?>

    <?php // echo $form->field($model, 'multa_pers_total') ?>

    <?php // echo $form->field($model, 'multa_total') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'motivo_baja') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
