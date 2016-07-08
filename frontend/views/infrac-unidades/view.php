<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\InfracUnidades */

$this->title = 'Detalle de unidad de infracciones';
$this->params['breadcrumbs'][] = ['label' => 'Unidades de infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infrac-unidades-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'unidad',
        ],
    ]) ?>

</div>
