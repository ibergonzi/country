<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\MensPersonas;

/* @var $this yii\web\View */
/* @var $model frontend\models\MensPersonas */

$this->title = 'Detalle de mensaje sobre persona';
$this->params['breadcrumbs'][] = ['label' => 'Mensajes sobre Personas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mens-personas-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'avisar_a',
            'mensaje',
            'model_id',
            'apellido',
            'nombre',
            'nombre2',
            'nro_doc',
            'created_by',
            'usuario_crea',
            'created_at:datetime',
            'updated_by',
            'usuario_borra',
            'updated_at:datetime',
			[
				'label' => 'Estado',
				'value' => MensPersonas::getEstados($model->estado),
			],
        ],
    ]) ?>

</div>
