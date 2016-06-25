<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\base\Model;

use yii\data\ArrayDataProvider;

use kartik\grid\GridView;

use yii\helpers\Html;
use yii\web\JsExpression;
use frontend\models\Personas;

use kartik\mpdf\Pdf;
use yii\db\Expression;



/**
 * AccesosController implements the CRUD actions for Accesos model.
 */
class CarnetsController extends Controller
{
	
    public function behaviors()
    {
		
        return [
			/*
			// se anula porque no se puede eliminar desde el index, sino desde el view (se llama por GET y no por POST)	        
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            */ 
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','add-lista','drop-lista'],
                        'allow' => true,
                        'roles' => ['accederCarnets'], 
                    ],                
		
                 ], // fin rules
             ], // fin access              
        ];
    }

  
    public function actionIndex($pdf=false,$lado='frente')
    {
		$model=new Personas();
		//if (isset($_POST['Personas'])) {
		if (Yii::$app->request->post()) {
			return $this->render('view',['pdf'=>false,'lado'=>'frente']);
		}
		if ($pdf) {	
		    $r=$this->renderPartial('view',['pdf'=>true,'lado'=>$lado]);		
			$pdf = new Pdf(); 
			
			$mpdf = $pdf->api;
			$mpdf->keep_table_proportions = true;			 
			$mpdf->WriteHtml($r); 
			Yii::trace($r);
			echo $mpdf->Output('tarjetas-'.$lado.'.pdf', 'D'); 	
		}			
		$listas=$this->refreshListas();
		return $this->render('carnets', [
			'model' => $model,
			'tmpListas'=>$listas,
		]);        
		        
	}    
    
    
    public function actionAddLista($grupo, $id)
    {
		// $grupo es solamente 'crntpersonas'
		
		if (empty($id)) {return;}
		
		// Se recupera de la sesion, cuando se graba se debe limpiar la session Personas asi: Yii::$app->session->remove('personas');
		$sess=\Yii::$app->session->get($grupo);	
		
		if (isset($id)) {
			// se pregunta si está seteado $sess sino la funcion in_array devuelve error
			if ($sess) {
						if (!in_array($id, $sess)) {
							$sess[]=$id;
							\Yii::$app->session[$grupo]=$sess;			
						} 
			} else {
						$sess[]=$id;
						\Yii::$app->session[$grupo]=$sess;
			}
	
			\Yii::$app->response->format = 'json';				
			$response=$this->refreshListas();
			return $response;
		}
	}
	
    public function actionDropLista($grupo, $id)
    {
		// $grupo es solamente 'crntpersonas'
		
		if (empty($id)) {return;}
		
		// Se recupera de la sesion, cuando se graba se debe limpiar la session Personas asi: Yii::$app->session->remove('personas');
		$sess=\Yii::$app->session->get($grupo);	
		
		if (isset($id)) {
			// se pregunta si está seteado $sess sino la funcion in_array devuelve error
			if ($sess) {
				if (count($sess)==1) {
					\Yii::$app->session->remove($grupo);
				} else {
					//Chequea que el id exista en la lista
					$key=array_search($id, $sess);
					if ($key || $key===0) {
						// se compara con === porque cuando no se encuentra devuelve falso (es decir 0)
						unset($sess[$key]);
						\Yii::$app->session[$grupo]=$sess;
					} //if ($key)
				} // if count($sess)==1
			} // if $sess
			
			
			\Yii::$app->response->format = 'json';				
			$response=$this->refreshListas();
			return $response;
			
		}
	}	
	

	public function refreshListas() {
		//$response=['ingpersonas'=>'','ingvehiculos'=>'','autorizantes'=>'','egrpersonas'=>'','egrvehiculos'=>''];
		$response=['crntpersonas'=>''];		
		foreach ($response as $grupo=>$valor) {
		
			// Se recupera de la sesion
			$sess=\Yii::$app->session->get($grupo);			

			if (!empty($sess)) {
				// Se crea el array vacio para el dataprovider
				$dp=[];				
				// La session solo contiene los IDs, se recorre el array y se completa $dp con el objeto que corresponda
				foreach ($sess as $p) {
							$dp[]=Personas::findOne($p);
				}
				$dataProvider = new ArrayDataProvider(['allModels'=>$dp]);			
			} else {
				// dataProvider vacio
				//return '';
				$response[$grupo]='';
				continue;
			} // if !empty $sess

			$columns=[
				[
					'header'=>'<span class="glyphicon glyphicon-trash"></span>',
					'attribute'=>'Acción',
					'format' => 'raw',
					'value' => function ($model, $index, $widget) {
											$url=Yii::$app->urlManager->createUrl(
												['carnets/drop-lista',
												'grupo'=>'crntpersonas', 
												'id' => isset($model->id)?$model->id:''
												]);
											return Html::a('<span class="glyphicon glyphicon-remove"></span>', 
												$url,
												['title' => 'Eliminar',
												 'onclick'=>'$.ajax({
													type     : "POST",
													cache    : false,
													url      : $(this).attr("href"),
													success  : function(r) {
																	$("#divlistapersonas").html(r["crntpersonas"]);
																}
												});return false;',
												]);			
								},
				],

				'id',
				'apellido',
				'nombre',
				'nombre2',
				'nro_doc',					
			];
			$heading='<i class="glyphicon glyphicon-user"></i>  Personas (Carnets)';
		
			
			$gvType=GridView::TYPE_INFO;

			 
			 
			$response[$grupo]=GridView::widget([
				'dataProvider' => $dataProvider,
				//'options'=>['id'=>$grupo],
				'layout'=>'{items}',
				'columns' => $columns,
				//'tableOptions' => ['class' => 'table table-striped table-condensed'], esto funciona si es el gridview de yii
				
				//opciones validas solo para el gridview de kartik
				'panel'=>[
					'type'=>$gvType,
					'heading'=>$heading,
					//'headingOptions'=>['class'=>'panel-heading'],
					'footer'=>false,
					'before'=>false,
					'after'=>false,
				],		
				'panelHeadingTemplate'=>'{heading}',			
				'resizableColumns'=>false,			
				'bordered'=>false,
				'striped'=>true,
				'condensed'=>true,
				'responsive'=>true,
				'hover'=>false,
				'toolbar'=>false,
				'export'=>false,			
			]);
		
		} //foreach $response
		return $response;
	
	}	


}
