<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Personas */

$this->title = 'Nueva Persona';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Personas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personas-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_formajax', [
        'model' => $model,
        'selector'=>$selector,
    ]) ?>

</div>
