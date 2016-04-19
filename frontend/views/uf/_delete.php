<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

use frontend\models\Uf;
$this->registerJs('$("#uf-motivo_baja").focus();',yii\web\View::POS_READY);
?>
<div class="uf-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'loteo',
            'manzana',
            'superficie:decimal',
			[
				'label' => 'Estado',
				'value' => Uf::getEstados($model->estado)
			],							
			//'estado',
			'motivo_baja',
	
        ],
    ]) ?>
    
    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'motivo_baja')->textInput(['maxlength' => true]) ?>

	<div class="form-group">
		<?= Html::submitButton('Eliminar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>        

</div>
