<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Agenda */

$this->title = 'Nuevo nombre';
$this->params['breadcrumbs'][] = ['label' => 'Agenda', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agenda-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
