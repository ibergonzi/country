<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\AccesosAutmanual;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosAutmanual */

$this->title = 'Detalle de autorización';
$this->params['breadcrumbs'][] = ['label' => 'Autorización de accesos manuales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-autmanual-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>

        <?= Html::a(($model->estado==AccesosAutmanual::ESTADO_ABIERTO)?'Cerrar periodo':'Abrir periodo', ['update', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro de cambiar el estado de la autorización?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'hora_desde:datetime',
            'hora_hasta:datetime',
			[
				'attribute' => 'Estado',
				'value' => AccesosAutmanual::getEstados($model->estado),
			],	
			'userCreatedBy.username',
			'created_at:datetime',
			'userUpdatedBy.username',
			'updated_at:datetime',
        ],
    ]) ?>

</div>
