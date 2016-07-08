<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MovimUfSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="movim-uf-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'desc_movim_uf') ?>

    <?= $form->field($model, 'cesion') ?>

    <?= $form->field($model, 'migracion') ?>

    <?= $form->field($model, 'fec_vto') ?>

    <?php // echo $form->field($model, 'manual') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
