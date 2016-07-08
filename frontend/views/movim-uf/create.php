<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MovimUf */

$this->title = 'Create Movim Uf';
$this->params['breadcrumbs'][] = ['label' => 'Movim Ufs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movim-uf-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
