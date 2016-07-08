<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Tiposdoc */
/* @var $form yii\widgets\ActiveForm */

//$this->registerJs('$("#REEMPLAZARPORIDPRIMERCAMPO").focus()', yii\web\View::POS_READY);

?>

<div class="tiposdoc-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['autofocus' => $model->isNewRecord,'readonly'=>!$model->isNewRecord]) ?>

    <?= $form->field($model, 'desc_tipo_doc')->textInput(['maxlength' => true,'autofocus' => !$model->isNewRecord,]) ?>

    <?= $form->field($model, 'desc_tipo_doc_abr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'persona_fisica')->dropDownList(frontend\models\Tiposdoc::getSiNo()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
