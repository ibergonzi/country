<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model frontend\models\MovimUf */
/* @var $form yii\widgets\ActiveForm */

//$this->registerJs('$("#REEMPLAZARPORIDPRIMERCAMPO").focus()', yii\web\View::POS_READY);

?>

<div class="movim-uf-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['autofocus' => $model->isNewRecord,'readonly'=>!$model->isNewRecord]) ?>

    <?= $form->field($model, 'desc_movim_uf')->textInput(['maxlength' => true,'autofocus' => !$model->isNewRecord,]) ?>

    <?= $form->field($model, 'cesion')->dropDownList(frontend\models\MovimUf::getSiNo()) ?>

    <?= $form->field($model, 'migracion')->dropDownList(frontend\models\MovimUf::getSiNo()) ?>
    
    <?= $form->field($model, 'fec_vto')->dropDownList(frontend\models\MovimUf::getSiNo()) ?> 
    
    <?= $form->field($model, 'manual')->dropDownList(frontend\models\MovimUf::getSiNo()) ?>           

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
