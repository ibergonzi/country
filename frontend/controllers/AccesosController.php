<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Accesos;
use frontend\models\AccesosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\base\Model;

use frontend\models\Personas;
use frontend\models\Vehiculos;
use yii\data\ArrayDataProvider;

use kartik\grid\GridView;

use yii\helpers\Html;

use frontend\models\Comentarios;
use frontend\models\Mensajes;




/**
 * AccesosController implements the CRUD actions for Accesos model.
 */
class AccesosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Accesos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccesosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Accesos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Accesos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Accesos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionBuscaVehiculos($id_persona)
    {
		// recupera los vehiculos utilizados por la persona en ingresos
		$vehiculos=Accesos::getVehiculosPorPersona($id_persona);
		
		// Si la persona es nueva o nunca tuvo accesos devuelve una bandera para que no se muestre el modal
		if (count($vehiculos)==0) {
			return 'NADA';
		}		

		// siempre debe agregar al principio de la lista los ids de vehiculos "sin vehiculo" y "bicicleta"
		
		// recorre el array para chequear si corresponde insertar al pcio de la lista 
		$estaIDsinVehiculo=false;
		$estaIDbicicleta=false;		
		foreach ($vehiculos as $vehiculo){
			if ($vehiculo['id_vehiculo'] == \Yii::$app->params['sinVehiculo.id']) {
				$estaIDsinVehiculo=true;
				break;
			}
		}
		foreach ($vehiculos as $vehiculo){
			if ($vehiculo['id_vehiculo'] == \Yii::$app->params['bicicleta.id']) {
				$estaIDbicicleta=true;
				break;
			}
		}
		
		// inserta al principio de la lista
		if (!$estaIDbicicleta) {
			array_unshift($vehiculos,['id_vehiculo'=>\Yii::$app->params['bicicleta.id'],'desc_vehiculo'=>'']);			
		}
		if (!$estaIDsinVehiculo) {
			array_unshift($vehiculos,['id_vehiculo'=>\Yii::$app->params['sinVehiculo.id'],'desc_vehiculo'=>'']);
		}

		Yii::trace($vehiculos);

		$aux=[];
		foreach ($vehiculos as $vehiculo){
			$aux[]=['id_vehiculo'=>$vehiculo['id_vehiculo'],
					'desc_vehiculo'=>Vehiculos::formateaVehiculoSelect2($vehiculo['id_vehiculo'])];
		}
		Yii::trace($aux);		
		
		
		return $this->renderAjax('_ingvehiculos', [
			'vehiculos' => $aux,
		]);
	}
	
    public function actionBuscaPersonas($id_vehiculo)
    {
		// recupera las personas que utilizaron el vehiculo
		$personas=Accesos::getPersonasPorVehiculo($id_vehiculo);

		// Si el vehiculo es nuevo o nunca tuvo accesos devuelve una bandera para que no se muestre el modal
		if (count($personas)==0) {
			return 'NADA';
		}
		
		$aux=[];
		foreach ($personas as $persona){
			$aux[]=['id_persona'=>$persona['id_persona'],
					'desc_persona'=>Personas::formateaPersonaSelect2($persona['id_persona'],false)];
		}

		
		return $this->renderAjax('_ingpersonas', [
			'personas' => $aux,
		]);
	}	
    
    
    public function actionAddListaArray($grupo, $id)
    {
		// se llama desde _ingpersonas.php, $id es un array
		$aux=explode(',',$id);
		foreach ($aux as $i) {
			$r=$this->actionAddLista($grupo, $i);
		}
		return $r;
	}
    
    public function actionAddLista($grupo, $id)
    {
		// $grupo puede ser 'personas','vehiculos','autorizantes','ufs'
		
		if (empty($id)) {return;}
		
		// Se recupera de la sesion, cuando se graba se debe limpiar la session Personas asi: Yii::$app->session->remove('personas');
		$sess=\Yii::$app->session->get($grupo);	
		
		if (isset($id)) {
			// se pregunta si está seteado $sess sino la funcion in_array devuelve error
			if ($sess) {
				if ($grupo =='vehiculos') {
					// el grupo vehiculos solo debe tener 1 elemento, si ya existia se reemplaza
						$sess[0]=$id;
						\Yii::$app->session[$grupo]=$sess;	
					
				} else {
					//Chequea que no se duplique el id en la lista
					if (!in_array($id, $sess)) {
						$sess[]=$id;
						\Yii::$app->session[$grupo]=$sess;			
					} 
				} 
			} else {
				$sess[]=$id;
				\Yii::$app->session[$grupo]=$sess;			
			}
			$response=$this->refreshLista($grupo);
			return $response;
			
		}
	}
	
    public function actionDropLista($grupo, $id)
    {
		// $grupo puede ser 'personas','vehiculos','autorizantes','ufs'		
		
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
					}
				}
			} 
			$response=$this->refreshLista($grupo);
			return $response;
			
		}
	}	
	
	public function actionRefrescaLista($grupo) 
	{
		$response=$this->refreshLista($grupo);
		return $response;
	}
	
	public function refreshLista($grupo) {
		// Se recupera de la sesion, cuando se graba se debe limpiar la session Personas asi: Yii::$app->session->remove('personas');
		$sess=\Yii::$app->session->get($grupo);			

        if (!empty($sess)) {
			// Se crea el array vacio para el dataprovider
			$dp=[];				
			// La session solo contiene los IDs, se recorre el array y se completa $dp con el objeto que corresponda
			foreach ($sess as $p) {
				switch ($grupo) {
					case 'personas':
						$dp[]=Personas::findOne($p);
						break;	
					case 'vehiculos':
						$dp[]=Vehiculos::findOne($p);
						break;							
					case 'autorizantes':
						// los autorizantes son personas
						$dp[]=Personas::findOne($p);
						break;	
				}	
			}
			$dataProvider = new ArrayDataProvider(['allModels'=>$dp]);			
		} else {
			// dataProvider vacio
			return '';
			/*
			$dataProvider = new ArrayDataProvider([
				'allModels'=>[ ['id'=>'', 'apellido'=>'', 'nombre'=>'','nombre2'=>'','nro_doc'=>''], ]
			]);
			*/					
		}
		switch ($grupo) {
			case 'personas':
				$columns=[
					[
						'header'=>'<span class="glyphicon glyphicon-trash"></span>',
						'attribute'=>'Acción',
						'format' => 'raw',
						'value' => function ($model, $index, $widget) {
												$url=Yii::$app->urlManager->createUrl(
													['accesos/drop-lista',
													'grupo'=>'personas', 
													'id' => isset($model->id)?$model->id:''
													]);
												return Html::a('<span class="glyphicon glyphicon-remove"></span>', 
													$url,
													['title' => 'Eliminar',
													 'onclick'=>'$.ajax({
														type     : "POST",
														cache    : false,
														url      : $(this).attr("href"),
														success  : function(response) {
																		$("#divlistapersonas").html(response);
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
					[
						'header'=>'',
						'attribute'=>'Mensajes',
						'format' => 'raw',
						'value' => function ($model, $index, $widget) {
											$c=Mensajes::getMensajesByModelId($model->className(),$model->id);

											if (!empty($c)) {
												$text='<span class="glyphicon glyphicon-alert" style="color:#FF8000"></span>';
												$titl='Ver mensaje';
											} else {
												$text='<span class="glyphicon glyphicon-envelope"></span>';
												$titl='Ingresar nuevo mensaje';
											}								
											$url=Yii::$app->urlManager->createUrl(
													['mensajes/create-ajax',
														'modelName'=>$model->className(),
														'modelID'=>$model->id]);							
											return Html::a($text, 
												$url,
											['title' => $titl,
											 'onclick'=>'$.ajax({
												type     :"POST",
												cache    : false,
												url  : $(this).attr("href"),
												success  : function(response) {
															$("#divcomentarionuevo").html(response);
															$("#modalcomentarionuevo").modal("show");
															}
											});return false;',
											]);			
									},						
					],
				];
				//$heading='<i class="glyphicon glyphicon-user"></i>  Personas';
				$heading='Personas';
				break;	
			case 'vehiculos':
				$columns=[
					[
						'header'=>'<span class="glyphicon glyphicon-trash"></span>',
						'attribute'=>'Acción',
						'format' => 'raw',
						'value' => function ($model, $index, $widget) {
												$url=Yii::$app->urlManager->createUrl(
													['accesos/drop-lista',
													'grupo'=>'vehiculos', 
													'id' => isset($model->id)?$model->id:''
													]);
												return Html::a('<span class="glyphicon glyphicon-remove"></span>', 
													$url,
													['title' => 'Eliminar',
													 'onclick'=>'$.ajax({
														type     : "POST",
														cache    : false,
														url      : $(this).attr("href"),
														success  : function(response) {
																		$("#divlistavehiculos").html(response);
																	}
													});return false;',
													]);			
									},
					],
					'id',
					'patente',
					'marca',
					'modelo',
					'color',
					[
						'header'=>'',
						'attribute'=>'Mensajes',
						'format' => 'raw',
						'value' => function ($model, $index, $widget) {
											$c=Mensajes::getMensajesByModelId($model->className(),$model->id);

											if (!empty($c)) {
												$text='<span class="glyphicon glyphicon-alert" style="color:#FF8000"></span>';
												$titl='Ver mensaje';
											} else {
												$text='<span class="glyphicon glyphicon-envelope"></span>';
												$titl='Ingresar nuevo mensaje';
											}								
											$url=Yii::$app->urlManager->createUrl(
													['mensajes/create-ajax',
														'modelName'=>$model->className(),
														'modelID'=>$model->id]);							
											return Html::a($text, 
												$url,
											['title' => $titl,
											 'onclick'=>'$.ajax({
												type     :"POST",
												cache    : false,
												url  : $(this).attr("href"),
												success  : function(response) {
															$("#divcomentarionuevo").html(response);
															$("#modalcomentarionuevo").modal("show");
															}
											});return false;',
											]);			
									},						
					],					

				];
				//$heading=Icon::show('car',[],Icon::FA). '  Vehiculos';
				$heading='Vehiculos';
				break;			
			case 'autorizantes':
				$columns=[
					[
						'header'=>'<span class="glyphicon glyphicon-trash"></span>',
						'attribute'=>'Acción',
						'format' => 'raw',
						'value' => function ($model, $index, $widget) {
												$url=Yii::$app->urlManager->createUrl(
													['accesos/drop-lista',
													'grupo'=>'autorizantes', 
													'id' => isset($model->id)?$model->id:''
													]);
												return Html::a('<span class="glyphicon glyphicon-remove"></span>', 
													$url,
													['title' => 'Eliminar',
													 'onclick'=>'$.ajax({
														type     : "POST",
														cache    : false,
														url      : $(this).attr("href"),
														success  : function(response) {
																		$("#divlistaautorizantes").html(response);
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
				//$heading=Icon::show('key',[],Icon::FA). '  Autorizantes';			
				$heading='Autorizantes';			
				break;									
		}			
		
		
		 
		$response=GridView::widget([
			'dataProvider' => $dataProvider,
			'layout'=>'{items}',
			'columns' => $columns,
			//'tableOptions' => ['class' => 'table table-striped table-condensed'], esto funciona si es el gridview de yii
			
			//opciones validas solo para el gridview de kartik
			'panel'=>[
				'type'=>GridView::TYPE_INFO,
				'heading'=>$heading,
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
		
		return $response;
	
	}

    public function actionIngreso()
    {
		/*
		\Yii::$app->session->remove('personas');
			
		//$sess=\Yii::$app->session['personas'];
		//$sess[]=9;
		//$sess[]=10;
		//$sess[]=11;
		//\Yii::$app->session['personas']=$sess;
		
		die();
		*/
		
		
        $model = new Accesos();
		$tmpListaPersonas=$this->refreshLista('personas');
		$tmpListaVehiculos=$this->refreshLista('vehiculos');				
		$tmpListaAutorizantes=$this->refreshLista('autorizantes');				
		return $this->render('ingreso', [
			//'model' => $searchModel,
			'model' => $model,
			'tmpListaPersonas'=>$tmpListaPersonas,
			'tmpListaVehiculos'=>$tmpListaVehiculos,			
			'tmpListaAutorizantes'=>$tmpListaAutorizantes,			
		]);        

        
		/*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('ingreso', [
                'model' => $model,
            ]);
        }
        */
    }

    

    /**
     * Updates an existing Accesos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Accesos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Accesos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Accesos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Accesos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
