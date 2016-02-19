<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'nombre2')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'id_tipo_doc')->dropDownList($model->listaTiposdoc) ?>

    <?= $form->field($model, 'nro_doc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'foto')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
