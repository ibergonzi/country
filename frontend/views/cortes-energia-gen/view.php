<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\CortesEnergiaGen */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cortes Energia Gens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cortes-energia-gen-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_cortes_energia',
            'id_generador',
            'hora_desde',
            'hora_hasta',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'estado',
            'motivo_baja',
        ],
    ]) ?>

</div>
