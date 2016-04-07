<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

use frontend\models\Portones;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PortonesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Corte de Energía');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cortes-index">

    <div class="jumbotron">
		<h2>
		<?php 
			if ($model->isNewRecord) {
				echo 'No hay ningún corte de energía en curso';
				$cartel='Presionando "Aceptar" da comienzo a un nuevo corte de energía';
			} else {
				echo 'El corte de energía comenzó el ' . Yii::$app->formatter->asDatetime($model->hora_desde);
				$cartel='Presionando "Aceptar" finaliza con el corte de energía';
			} 
		?>
		</h2>		
		<p class="lead"><?= $cartel ?></p>		
	</div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
  
    <?php $form = ActiveForm::begin(); ?>    

	<?= Html::hiddenInput('accion','accion') ?>
	
		<div class="form-group">
			<?= Html::submitButton('Aceptar', ['class' => 'btn btn-danger']) ?>
		</div>		
	<?php ActiveForm::end(); ?>		
 
</div>
