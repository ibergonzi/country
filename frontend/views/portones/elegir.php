<?php

use yii\helpers\Html;
use yii\grid\GridView;

use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PortonesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Elegir Portón');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portones-index">

    <h2><?php 
			if (\Yii::$app->session->get('porton')) {
				echo 'PORTON SELECCIONADO:' .  \Yii::$app->session->get('porton');
			} else
			{
				echo 'No ha elegido ningún portón para continuar operando';
			}
		?>
	</h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php
		echo SwitchInput::widget([
			'name' => 'eligeporton',
			'type' => SwitchInput::RADIO,
			'items' => [
				['label' => 'Portón 1', 'value' => 1],
				['label' => 'Portón 2', 'value' => 2],
				['label' => 'Portón 3', 'value' => 3],
			],
			'pluginOptions' => ['size' => 'large',
					'onText' => 'SI',
					'offText' => 'NO',			
					],
			'labelOptions' => ['style' => 'font-size: 16px'],
		]);
		\Yii::$app->session->set('porton',1);
?>

 
</div>
