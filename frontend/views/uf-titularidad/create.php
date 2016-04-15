<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\UfTitularidad */

$this->title = 'Titularidad sobre UF: '.$model->id_uf;
/*
$this->params['breadcrumbs'][] = ['label' => 'Titularidad', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
*/
?>
<div class="uf-titularidad-create">

    <?= $this->render('_form', [
        'model' => $model,
        'titPers'=> $titPers,   
		'tmpListas'=>$tmpListas,               
    ]) ?>

</div>
