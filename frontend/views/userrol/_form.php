<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use frontend\models\UserRol;


/* @var $this yii\web\View */
/* @var $model frontend\models\UserRol */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-rol-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
    //echo $form->field($model, 'id')->textInput() 
    ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?php 
    //echo $form->field($model, 'email')->textInput(['maxlength' => true]) 
    ?>

    <?php 
    echo $form->field($model, 'item_name')->dropDownList(
             ArrayHelper::map(UserRol::listaRoles(), 'name', 'description')
        );
    ?>

    <?php 
    // echo $form->field($model, 'name')->textInput(['maxlength' => true]) 
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
