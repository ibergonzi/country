<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accesos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_persona',
            'ing_id_vehiculo',
            'ing_fecha',
            'ing_hora',
            'ing_id_porton',
            'ing_id_user',
            'egr_id_vehiculo',
            'egr_fecha',
            'egr_hora',
            'egr_id_porton',
            'egr_id_user',
            'id_concepto',
            'motivo',
            'cant_acomp',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'estado',
            'motivo_baja',
        ],
    ]) ?>

</div>
