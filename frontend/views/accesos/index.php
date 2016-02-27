<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AccesosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Accesos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Accesos'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
