<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AuthItem */

$this->title = 'Nuevo rol';
$this->params['breadcrumbs'][] = ['label' => 'Roles en el sistema', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
