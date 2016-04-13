<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\TitularidadVistaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="titularidad-vista-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_titularidad') ?>

    <?= $form->field($model, 'id_uf') ?>

    <?= $form->field($model, 'desc_movim_uf') ?>

    <?= $form->field($model, 'fec_desde') ?>

    <?php // echo $form->field($model, 'fec_hasta') ?>

    <?php // echo $form->field($model, 'exp_telefono') ?>

    <?php // echo $form->field($model, 'exp_direccion') ?>

    <?php // echo $form->field($model, 'exp_localidad') ?>

    <?php // echo $form->field($model, 'exp_email') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'id_persona') ?>

    <?php // echo $form->field($model, 'apellido') ?>

    <?php // echo $form->field($model, 'nombre') ?>

    <?php // echo $form->field($model, 'nombre2') ?>

    <?php // echo $form->field($model, 'desc_tipo_doc_abr') ?>

    <?php // echo $form->field($model, 'nro_doc') ?>

    <?php // echo $form->field($model, 'superficie') ?>

    <?php // echo $form->field($model, 'coeficiente') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
