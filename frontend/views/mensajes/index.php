<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MensajesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mensajes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mensajes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Mensajes'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'avisar_a',
            'mensaje',
            'model',
            'model_id',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            // 'estado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
