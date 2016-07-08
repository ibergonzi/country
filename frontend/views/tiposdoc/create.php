<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Tiposdoc */

$this->title = 'Create Tiposdoc';
$this->params['breadcrumbs'][] = ['label' => 'Tiposdocs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tiposdoc-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
