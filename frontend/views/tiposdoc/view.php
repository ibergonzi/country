<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Tiposdoc */

$this->title = 'Detalle de tipo de documento';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de documento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tiposdoc-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'desc_tipo_doc',
            'desc_tipo_doc_abr',
			[
				'attribute' => 'persona_fisica',
				'value' => frontend\models\Tiposdoc::getSiNo($model->persona_fisica)
			],
        ],
    ]) ?>

</div>
