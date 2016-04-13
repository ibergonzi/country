<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidad */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Uf Titularidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-titularidad-view">

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
            'id_uf',
            'tipo_movim',
            'fec_desde',
            'fec_hasta',
            'exp_telefono',
            'exp_direccion',
            'exp_localidad',
            'exp_email:email',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'estado',
            'motivo_baja',
            'ultima',
        ],
    ]) ?>

</div>
