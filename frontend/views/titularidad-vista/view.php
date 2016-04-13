<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\TitularidadVista */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Titularidad Vistas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="titularidad-vista-view">

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
            'id_titularidad',
            'id_uf',
            'desc_movim_uf',
            'fec_desde',
            'fec_hasta',
            'exp_telefono',
            'exp_direccion',
            'exp_localidad',
            'exp_email:email',
            'tipo',
            'id_persona',
            'apellido',
            'nombre',
            'nombre2',
            'desc_tipo_doc_abr',
            'nro_doc',
            'superficie',
            'coeficiente',
            'observaciones',
        ],
    ]) ?>

</div>
