<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Uf */

$this->title = 'Create Uf';
$this->params['breadcrumbs'][] = ['label' => 'Ufs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
