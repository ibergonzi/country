<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// prueba de usar con teclas el gridview
/*
use app\assets\GridkeysAsset;
GridkeysAsset::register($this);

$this->registerJs('$(":input.flat:first").focus();',yii\web\View::POS_READY);
*/

$this->title = Yii::t('app', 'Usuarios del sistema');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>




    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'tableOptions'=> ['class'=>'table  table-bordered table-hover'
        //],
        //table-striped
        /* prueba para usar con teclas
        'rowOptions'=> function ($model, $key, $index, $column) {
						return [
								'style'=>'cursor: pointer',
								'onclick'=> 'location.href="' . Yii::$app->UrlManager->createUrl(['user/view', 
                                             'id' => $key]) . '"',
												];
						},
		*/
		'panel'=>[
			'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-eye-open"></i> '.$this->title.'</h3>',
			'type'=>'primary',
			'before'=>'&nbsp;', // para que dibuje el toolbar (cuac)
		],		
		'condensed'=>true, 				
						
        'columns' => [

			/* prueba para usar con teclas
            [
       		'attribute'=>'id',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $column) {
                        return Html::textInput('usrnm'.$index, $model->id,
                                ['readonly' => true,'class'=>'flat','style'=>'width:20px;background-color:#fff;border:none']
                                );
                        },
    
                    //'checked' => '($data->reclamo)'
            		],		
           	*/
            'id',		
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',

           [
                'attribute'=>'descRolUsuario',
                'value'=>'authAssignment.authItem.description',   
           ],              

            ['class' => 'yii\grid\ActionColumn'],
        ],
 
    ]); ?>

</div>
