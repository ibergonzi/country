<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */

$this->title = 'Eliminar autorización';
$this->params['breadcrumbs'][] = ['label' => 'Autorizaciones (eventuales y permanentes)', 'url' => ['autorizados/index']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de autorización', 'url' => ['autorizados/view','id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personas-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
		'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view'],        
        'attributes' => [
            'persona.apellido',
			'persona.nombre',  
			'persona.nro_doc',
            'autorizante.apellido',
			'autorizante.nombre',  
			'autorizante.nro_doc',
            'id_uf',
            'fec_desde:date',
            'fec_hasta:date',
        ],
    ]) ?>

    <?= $this->render('_delete', [
        'model' => $model,
    ]) ?>

</div>
