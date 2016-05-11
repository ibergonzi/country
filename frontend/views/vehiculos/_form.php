<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\Vehiculos;
use yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $model frontend\models\Vehiculos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vehiculos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'patente')->textInput(['maxlength' => true,'style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'marca')->widget(AutoComplete::className(),[
            'model' => $model,
            'attribute' => 'marca',
            'options'=>[
                'style'=>'text-transform: uppercase',
                'class'=>'form-control',
                'max-height'=>'100px',
                'overflow-y'=>'auto',
                'overflow-x'=>'hidden',
 
            ],
            'clientOptions' => [
                'source' => Vehiculos::getMarcasVehiculos(),
            ], 
        ])
    ?>

     <?= $form->field($model, 'modelo')->widget(AutoComplete::className(),[
            'model' => $model,
            'attribute' => 'modelo',
            'options'=>[
                'style'=>'text-transform: uppercase',
                'class'=>'form-control',
                'max-height'=>'100px',
                'overflow-y'=>'auto',
                'overflow-x'=>'hidden',
 
            ],
            'clientOptions' => [
                'source' => Vehiculos::getModelosVehiculos(),
            ], 
        ])
    ?>

     <?= $form->field($model, 'color')->widget(AutoComplete::className(),[
            'model' => $model,
            'attribute' => 'color',
            'options'=>[
                'style'=>'text-transform: uppercase',
                'class'=>'form-control',
                'max-height'=>'100px',
                'overflow-y'=>'auto',
                'overflow-x'=>'hidden',
 
            ],
            'clientOptions' => [
                'source' => Vehiculos::getColoresVehiculos(),
            ], 
        ])
    ?>
    
    <?= $form->field($model, 'estado')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
