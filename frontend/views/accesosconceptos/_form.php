<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\AccesosConceptos;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosConceptos */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('$("#accesosconceptos-concepto").focus()', yii\web\View::POS_READY);
?>

<div class="accesos-conceptos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'concepto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'req_tarjeta')->dropDownList(AccesosConceptos::getSiNo()) ?>

    <?= $form->field($model, 'req_seguro')->dropDownList(AccesosConceptos::getSiNo())?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
