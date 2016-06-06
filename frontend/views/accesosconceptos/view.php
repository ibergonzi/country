<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\AccesosConceptos;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccesosConceptos */

$this->title = 'Detalle de concepto de acceso ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Conceptos de accesos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-conceptos-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(($model->estado==AccesosConceptos::ESTADO_ACTIVO)?'Deshabilita':'Habilita', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Confirma el cambio de estado?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'concepto',
			[
				'attribute' => 'req_tarjeta',
				'value' => AccesosConceptos::getSiNo($model->req_tarjeta)
			],
			[
				'attribute' => 'req_seguro',
				'value' => AccesosConceptos::getSiNo($model->req_seguro)
			],
			'userCreatedBy.username',
			'created_at:datetime',
			'userUpdatedBy.username',
			'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => AccesosConceptos::getEstados($model->estado)
			],				            
        ],
    ]) ?>

</div>
