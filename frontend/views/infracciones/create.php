<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Infracciones */

$this->title = 'Nueva infracción';
$this->params['breadcrumbs'][] = ['label' => 'Infracciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="infracciones-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
