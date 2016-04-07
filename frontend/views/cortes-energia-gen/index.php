<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CortesEnergiaGenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Novedades de generadores';
$this->params['breadcrumbs'][] = ['label' => 'Cortes de energía', 'url' => ['cortes-energia/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cortes-energia-gen-index">

    <h3><?= Html::encode($this->title. ' ' . 
		Yii::$app->formatter->asDatetime($parent->hora_desde) .
		' - '. 
		Yii::$app->formatter->asTime($parent->hora_hasta)) ?>
    </h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [


            //'id',
            //'id_cortes_energia',
            'id_generador',
            'hora_desde',
            'hora_hasta',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            // 'estado',
            // 'motivo_baja',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
