<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\AutorizadosHorarios;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */

$this->title = 'Eliminar día';
$this->params['breadcrumbs'][] = ['label' => 'Autorizaciones (eventuales y permanentes)', 
	'url' => ['autorizados/index']];
$this->params['breadcrumbs'][] = ['label' => 'Horarios de autorización', 
	'url' => ['autorizados-horarios/index','idParent'=>$parent->id]];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de Horario de autorización', 
	'url' => ['autorizados-horarios/view','id'=>$model->id]];	
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personas-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
		'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],            
        'attributes' => [
 			[
				'label' => 'Día',
				'value' => AutorizadosHorarios::getDias($model->dia)
			],
            'hora_desde',
            'hora_hasta',
        ],
    ]) ?>

    <?= $this->render('_delete', [
        'model' => $model,
    ]) ?>

</div>
