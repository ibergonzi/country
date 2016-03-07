<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AutorizantesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Autorizantes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="autorizantes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Autorizantes'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_persona',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            // 'estado',
            // 'motivo_baja',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>