<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserRolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asignación de roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-rol-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
 

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

           
            'username',
            'email:email',

            //'item_name',
            'description',
            
           ['class' => 'yii\grid\ActionColumn',
           'template' => '{update}',
           
           ],
 
        ],
    ]); ?>

</div>
