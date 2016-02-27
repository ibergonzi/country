<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Accesos */

$this->title = Yii::t('app', 'Create Accesos');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accesos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
