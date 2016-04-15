<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UfSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ufs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Uf', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'loteo',
            'manzana',
            'superficie',
            'coeficiente',
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
