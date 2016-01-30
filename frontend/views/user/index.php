<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// registra 
use app\assets\GridkeysAsset;
GridkeysAsset::register($this);

$this->registerJs('$(":input.flat:first").focus();',yii\web\View::POS_READY);


$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'tableOptions'=> ['class'=>'table  table-bordered table-hover'
        //],
        //table-striped
        'rowOptions'=> function ($model, $key, $index, $column) {
						return [
								'style'=>'cursor: pointer',
								'onclick'=> 'location.href="' . Yii::$app->UrlManager->createUrl(['user/view', 
                                             'id' => $key]) . '"',
												];
						},
		'panel'=>[
			'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-eye-open"></i> '.$this->title.'</h3>',
			'type'=>'primary',
			'before'=>'&nbsp;', // para que dibuje el toolbar (cuac)
		],		
		'condensed'=>true, 				
						
        'columns' => [
            [
				'class' => 'yii\grid\SerialColumn',
				            
            ],


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
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            // 'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
 
    ]); ?>

</div>
