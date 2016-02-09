<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Libro */

$this->title = 'Detalle de entrada de Libro de guardia';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Libro de guardia'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="libro-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'texto',
            'idporton',
            'userCreatedBy.username',
            'created_at:datetime',
            'userUpdatedBy.username',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
