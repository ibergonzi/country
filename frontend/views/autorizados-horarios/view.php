<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\AutorizadosHorarios */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Autorizados Horarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="autorizados-horarios-view">

    <h3><?= Html::encode($this->title) ?></h3>

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
            'id_autorizado',
            'dia',
            'hora_desde',
            'hora_hasta',
            'created_by',
            'create_at',
            'updated_by',
            'updated_at',
            'estado',
            'motivo_baja',
        ],
    ]) ?>

</div>
