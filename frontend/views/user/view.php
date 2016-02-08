<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Detalle de usuario';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?= Html::encode($this->title) ?></h2>
<div class='container'>
	
	<div class='row'>

		<div class="col-md-9">

			<div class="user-view">





				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'id',
						'username',
						//'auth_key',
						//'password_hash',
						//'password_reset_token',
						'email:email',
						//'status',
						//'created_at',
						//'updated_at',
						'authAssignment.authItem.description',
						/*
						[
							'attribute'=>'descRolUsuario',
							'value'=>function ($data) { return $data->authAssignment->authItem->description;},   
					   ], 
					   */       
					],
				]) ?>

			</div>
		</div>	
		
		<div class="col-md-3">

			<?php
				if (!empty($model->foto)) {
					echo Html::img(Yii::$app->urlManager->createUrl('images/usuarios/'.$model->foto),
						['class'=>'img-thumbnail pull-right']);
				}
				else
				{
					echo Html::img(Yii::$app->urlManager->createUrl('images/sinfoto.png'),
						['class'=>'img-thumbnail pull-right']);
				}
			?>

		</div>	
	</div>
</div>		
