<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\InfraccionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Infracciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infracciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Infracciones', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'id_uf',
            'id_vehiculo',
            'id_persona',
            'fecha',
            'hora',
            'nro_acta',
            'lugar',
            'id_concepto',
            'id_informante',
            'descripcion',
            'notificado',
            'fecha_verif',
            'verificado',
            //'foto',
            'multa_unidad',
            // 'multa_monto',
            // 'multa_pers_cant',
            // 'multa_pers_monto',
            // 'multa_pers_total',
            'multa_total',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            'estado',
            // 'motivo_baja',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
