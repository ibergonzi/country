<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UfTitularidadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Movs.Titularidad U.F.'.$uf;
$this->params['breadcrumbs'][] = ['label' => 'Unidades funcionales', 'url' => ['uf/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-titularidad-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [

            //'id',
            //'id_uf',
            'tipoMovim.desc_movim_uf',
            'fec_desde:date',
            'fec_hasta:date',
            'exp_telefono',
            'exp_direccion',
            'exp_localidad',
            'exp_email:email',
            //'created_by',
            //'created_at',
            // 'updated_by',
            // 'updated_at',
            // 'estado',
            // 'motivo_baja',
            // 'ultima',

            ['class' => 'yii\grid\ActionColumn',
			 'template' => '{view}',              
            ],
        ],
    ]); ?>
</div>
