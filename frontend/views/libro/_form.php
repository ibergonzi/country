<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Libro */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('$("#libro-texto").focus()', yii\web\View::POS_READY);
?>

<div class="libro-form">


	<?php
	if (!\Yii::$app->session->get('porton')) {

		echo Html::a('Seleccionar portón', ['portones/elegir',], ['class' => 'btn btn-primary']); 
	}
	else 
	{
		$form = ActiveForm::begin();

		echo $form->field($model, 'idporton')->textInput(['value'=>\Yii::$app->session->get('porton'),
					'disabled' => true]);
		echo $form->field($model, 'texto')->textInput(['maxlength' => true]);
	?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php 
		ActiveForm::end();

    	}
    
    ?>

</div>
