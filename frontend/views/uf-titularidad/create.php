<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidad */

$this->title = 'Create Uf Titularidad';
$this->params['breadcrumbs'][] = ['label' => 'Uf Titularidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-titularidad-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
