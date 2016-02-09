<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Persona */

$this->title = 'Nueva persona';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Personas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="persona-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_formajax', [
        'model' => $model,
    ]) ?>

</div>