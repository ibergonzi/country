<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Uf */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uf-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'id')->textInput(['readonly' => !$model->isNewRecord]); 
		$model->superficie = yii::$app->formatter->asDecimal($model->superficie,2);
    ?>

    <?= $form->field($model, 'loteo')->textInput() ?>

    <?= $form->field($model, 'manzana')->textInput() ?>

    <?= $form->field($model, 'superficie')->textInput() ?>
    
    <?php
		echo $form->field($model, 'estado')->dropDownList($model->estadosModif);
    ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
