<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Comentarios */
/* @var $form yii\widgets\ActiveForm */

	$js = 
<<<JS
	$('form#form-comentarionuevo-ajax').
		on('beforeSubmit', function(e) {
			var form = $(this);
			$.post(
				form.attr('action'),
				form.serialize()
			).done(function(result) {
				$('#modalcomentarionuevo').modal('hide');
				//location.reload();
			});
			return false;
			}).
		on('submit', function(e){
			e.preventDefault();
		});
JS;
$this->registerJs($js,yii\web\View::POS_READY);


?>

<div class="comentarios-form">

    <?php $form = ActiveForm::begin(
    		[
			'id' => 'form-comentarionuevo-ajax',
		]    
    ); ?>

    <?= $form->field($model, 'comentario')->textArea(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
