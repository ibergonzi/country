<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Libro */

$this->title = Yii::t('app', 'Nueva entada al libro de guardia');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Libro de guardia'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="libro-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
