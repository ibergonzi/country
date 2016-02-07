<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;

use kartik\widgets\SwitchInput;

use frontend\models\Portones;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PortonesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Elegir Portón');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portones-index">

    <div class="jumbotron">
		<H2>
		<?php 
			if (\Yii::$app->session->get('porton')) {
				echo 'Activado: ' .  \Yii::$app->session->get('porton');
				$cartel=Portones::findOne(\Yii::$app->session->get('porton'))->descripcion;
			} else
			{
				echo 'No ha elegido ningún portón para continuar operando';
				$cartel='';
			}
		?>
		</H2>		
		<p class="lead"><?= $cartel ?></p>		
	</div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <hr/>    
    <?php $form = ActiveForm::begin(); ?>    
		<?php
		$opt=[];
		foreach ($model as $porton) {
			$opt[] = ['label'=>$porton->descripcion, 'value'=>$porton->id];
		}
		
		echo SwitchInput::widget([
			'name' => 'eligeporton',
			'type' => SwitchInput::RADIO,
			'items' => $opt,
			'value' => \Yii::$app->session->get('porton'),
			'pluginOptions' => ['size' => 'large',
					'onText' => 'SI',
					'offText' => 'NO',			
					],
			'labelOptions' => ['style' => 'font-size: 16px'],
		]);
		//\Yii::$app->session->set('porton',1); 		   
		?>
		<hr/>
		<div class="form-group">
			<?= Html::submitButton('Confirmar la selección', ['class' => 'btn btn-success']) ?>
		</div>		
	<?php ActiveForm::end(); ?>		
 
</div>
