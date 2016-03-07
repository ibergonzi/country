<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\Comentarios;

use yii\widgets\DetailView;

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

// esto es para que las columnas del detailView no cambien de tamaño
$this->registerCss('table.detail-view th {width: 25%;} table.detail-view td {width: 75%;}');	

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

    <?php 
    	$models=Comentarios::find()->where(['model'=>$modelNameOrigen,'model_id'=>$modelIDOrigen])
			->orderBy(['created_at'=>SORT_DESC])->all();
		$primeraVez=true;	
		foreach($models as $m) {
			if ($primeraVez) {
				echo '<hr/>';
				echo '<p>Cantidad de comentarios '.kartik\helpers\Html::badge(count($models)).'</p>';
				$primeraVez=false;
			}
			echo DetailView::widget([
			'model' => $m,
			'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],
			'attributes' => [
				//'id',
				'comentario',
				//'model',
				//'model_id',
				'userCreatedBy.username',
				'created_at:datetime',
				//'updated_by',
				//'updated_at',
				],
			]);
			echo '<hr/>';
		}
    ?>

</div>
