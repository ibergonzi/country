<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {
	$this->registerJs('$("#authitem-name").focus()', yii\web\View::POS_READY);
} else {
	$this->registerJs('$("#authitem-description").focus()', yii\web\View::POS_READY);	
}


?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Aceptar' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
