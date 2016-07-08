<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Generadores */
/* @var $form yii\widgets\ActiveForm */

//$this->registerJs('$("#REEMPLAZARPORIDPRIMERCAMPO").focus()', yii\web\View::POS_READY);

?>

<div class="generadores-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['autofocus' => $model->isNewRecord,'readonly'=>!$model->isNewRecord]) ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true,'autofocus' => !$model->isNewRecord,]) ?>

    <?= $form->field($model, 'activo')->dropDownList(frontend\models\Generadores::getSiNo()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
