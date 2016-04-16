<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UfTitularidadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Uf Titularidads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-titularidad-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Uf Titularidad', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'id_uf',
            'tipo_movim',
            'fec_desde',
            'fec_hasta',
            // 'exp_telefono',
            // 'exp_direccion',
            // 'exp_localidad',
            // 'exp_email:email',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            // 'estado',
            // 'motivo_baja',
            // 'ultima',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
