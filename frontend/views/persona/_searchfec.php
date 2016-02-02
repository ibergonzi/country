<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="persona-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'fecdesde')->widget(DateControl::className(),
						['type' =>DateControl::FORMAT_DATE,
						 'options'=>[
							 'id'=>'fcd',
							 'pluginEvents'=>[ 'clearDate'=>'function(e) { 
												$("#fcd").val("");
											}'
								],	
							]
						]
	) ?> 
    <?= $form->field($model, 'fechasta')->widget(DateControl::className(),
						['type' =>DateControl::FORMAT_DATE,
						 'options'=>[
							 'id'=>'fch',
							 'pluginEvents'=>[ 'clearDate'=>'function(e) { 
												$("#fch").val("");
											}'
								],	
							]						
						]
	) ?> 

 

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
