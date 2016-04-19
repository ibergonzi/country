<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Uf */

$this->title = 'Nueva U.F.';
$this->params['breadcrumbs'][] = ['label' => 'Ufs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uf-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
