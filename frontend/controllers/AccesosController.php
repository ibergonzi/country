<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\base\Model;

use yii\data\ArrayDataProvider;

use kartik\grid\GridView;

use yii\helpers\Html;

use frontend\models\Accesos;
use frontend\models\AccesosSearch;
use frontend\models\Personas;
use frontend\models\Vehiculos;
use frontend\models\Comentarios;
use frontend\models\Autorizantes;
use frontend\models\Mensajes;
use frontend\models\AccesosAutorizantes;
use frontend\models\AccesosUf;
use frontend\models\AccesosConceptos;
use frontend\models\Uf;


use yii\db\Expression;




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
    
    public function actionBuscaVehiculos($grupo,$id_persona)
    {
		// recupera los vehiculos utilizados por la persona en ingresos/egresos
		$vehiculos=Accesos::getVehiculosPorPersona($id_persona,false);
		
		// Si la persona es nueva o nunca tuvo accesos devuelve una bandera para que no se muestre el modal
		if (count($vehiculos)==0) {
			return 'notFound';
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

		$aux=[];
		foreach ($vehiculos as $vehiculo){
			$aux[]=['id_vehiculo'=>$vehiculo['id_vehiculo'],
					'desc_vehiculo'=>Vehiculos::formateaVehiculoSelect2($vehiculo['id_vehiculo'])];
		}
		
		// el parametro true se refiere a $ultimoVehiculo, es decir, que traiga el vehiculo del último ingreso de la persona
		// esto se hace para armar la seleccion
		$ultVehiculo=Accesos::getVehiculosPorPersona($id_persona,true);

		// ultVehiculo es un array de arrays [idVehiculo=>valor]
		$seleccion=[];
		foreach ($ultVehiculo as $v) {
			foreach ($v as $key=>$valor) {
				$seleccion[]=$valor;
			}
		}		
		
		if ($grupo=='ingpersonas') {
			return $this->renderAjax('_ingvehiculos', [
				'vehiculos' => $aux,
				'seleccion' => $seleccion,
			]);
		} else {
			return $this->renderAjax('_egrvehiculos', [
				'vehiculos' => $aux,
				'seleccion' => $seleccion,
			]);
		}
	}
	
	
    public function actionBuscaPersonas($grupo,$id_vehiculo)
    {
		// recupera las personas que utilizaron el vehiculo alguna vez,
		// el parametro false se refiere a $ultimasPersonas, es decir, que traiga todas las personas
		$personas=Accesos::getPersonasPorVehiculo($id_vehiculo,false);

		// Si el vehiculo es nuevo o nunca tuvo accesos devuelve una bandera para que no se muestre el modal
		if (count($personas)==0) {
			return 'notFound';
		}
		
		$aux=[];
		foreach ($personas as $persona){
			$aux[]=['id_persona'=>$persona['id_persona'],
					'desc_persona'=>Personas::formateaPersonaSelect2($persona['id_persona'],false)];
		}
		
		// el parametro true se refiere a $ultimasPersonas, es decir, que traiga las personas del último ingreso del vehic.
		// esto se hace para armar la seleccion
		$ultPersonas=Accesos::getPersonasPorVehiculo($id_vehiculo,true);

		// ultPersonas es un array de arrays [idPersona=>valor]
		$seleccion=[];
		foreach ($ultPersonas as $p) {
			foreach ($p as $key=>$valor) {
				$seleccion[]=$valor;
			}
		}

		if ($grupo=='ingvehiculos') {		
			return $this->renderAjax('_ingpersonas', [
				'personas' => $aux,
				'seleccion'=>$seleccion,
			]);
		} else {
			return $this->renderAjax('_egrpersonas', [
				'personas' => $aux,
				'seleccion'=>$seleccion,
			]);
		}
	}	

	public function actionPideSeguro($idPersona)
	{
		// solo se usa para mostrar el formulario que pide el seguro
		$p=Personas::findOne($idPersona);
		return $this->renderAjax('_vtoseguro', [
			'idPersona' => $idPersona,
			'fec'=>$p->vto_seguro,
		]);
	}
	
	public function actionUpdateVtoSeguro($idPersona,$fecseguro) 
	{
		// actualiza el vto del seguro
		$p=Personas::findOne($idPersona);
        $faux=\DateTime::createFromFormat('d/m/Y',$fecseguro);
		$p->vto_seguro=$faux->format('Y-m-d');
		$p->save();
		\Yii::$app->response->format = 'json';				
		return $this->refreshListas();		
	}
	
	public function actionBuscaPorId()
	{
		// solo se usa para mostrar el formulario que busca la persona por ID o codigo de barras
		return $this->renderAjax('_buscaporid');
	}	
	
	public function actionBuscaPersonaPorId($idPersonaPorId) 
	{
		$p=Personas::findOne($idPersonaPorId);
		if ($p) {
			return $p->id;
		} else {
			return 'notFound';
		}
	}
	    
    
    public function actionAddListaArray($grupo, $id)
    {
		// se llama desde _ingpersonas.php, $id es un array
		$aux=explode(',',$id);
		foreach ($aux as $i) {
			$r=$this->actionAddLista($grupo, $i);
		}
		\Yii::$app->response->format = 'json';		
		return $r;
	}
    
    public function actionAddLista($grupo, $id)
    {
		// $grupo puede ser 'ingpersonas','ingvehiculos','autorizantes','egrpersonas','egrvehiculos'
		
		if (empty($id)) {return;}
		
		// Se recupera de la sesion, cuando se graba se debe limpiar la session Personas asi: Yii::$app->session->remove('personas');
		$sess=\Yii::$app->session->get($grupo);	
		
		if (isset($id)) {
			// se pregunta si está seteado $sess sino la funcion in_array devuelve error
			if ($sess) {
				if ($grupo =='ingvehiculos' || $grupo == 'egrvehiculos') {
					// el grupo vehiculos solo debe tener 1 elemento, si ya existia se reemplaza
						$sess[0]=$id;
						\Yii::$app->session[$grupo]=$sess;	
					
				} else {
					if ($grupo == 'autorizantes') {
						$aut=Autorizantes::find()->where(['id_persona'=>$id])->all();
						foreach ($aut as $a) {
							//Chequea que no se duplique el id en la lista
							if (!in_array($a->id, $sess)) {
								$sess[]=$a->id;
								\Yii::$app->session[$grupo]=$sess;			
							} 
						}
					} else {
						//Chequea que no se duplique el id en la lista
						if (!in_array($id, $sess)) {
							$sess[]=$id;
							\Yii::$app->session[$grupo]=$sess;			
						} 
					}
				} 
			} else {
				if ($grupo == 'autorizantes') {
					$aut=Autorizantes::find()->where(['id_persona'=>$id])->all();
					foreach ($aut as $a) {
						$sess[]=$a->id;
						\Yii::$app->session[$grupo]=$sess;			
					}
					
				} else {
					$sess[]=$id;
					\Yii::$app->session[$grupo]=$sess;
				}
			}
		
			
			\Yii::$app->response->format = 'json';				
			$response=$this->refreshListas();
			return $response;
			
		}
	}
	
    public function actionDropLista($grupo, $id)
    {
		// $grupo puede ser 'ingpersonas','ingvehiculos','autorizantes','egrpersonas','egrvehiculos'
		
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
	
	
    public function actionBuscaUltIngreso($grupo, $id)
    {
		// $grupo puede ser 'ingpersonas' o 'ingvehiculos'
		
		if (empty($id)) {return;}
		if ($grupo !== 'ingpersonas' && $grupo !== 'ingvehiculos') {return;}
		
		if ($grupo=='ingpersonas') {
			$ult=Accesos::find()->where(['id_persona'=>$id])
				->andWhere(['>','id_concepto',0])
				->orderBy(['id' => SORT_DESC])->asArray()->one();
		} else {
			$ult=Accesos::find()->where(['ing_id_vehiculo'=>$id])
				->andWhere(['>','id_concepto',0])
				->orderBy(['id' => SORT_DESC])->asArray()->one();			
		}
		
		Yii::trace($ult);
		\Yii::$app->response->format = 'json';
		
		$a=Accesos::findOne($ult['id']);
		
		if (!empty($a)) {
			$raux='';
			// cuando se llama a actionAddLista se están cargando TODAS las uf de cada persona
			// está hecho a proposito, es decir, no se muestran las ufs del ultimo acceso, sino que se muestran
			// las personas del ultimo acceso y se cargan TODAS sus uf
			foreach ($a->accesosAutorizantes as $aa) {
				$raux=$this->actionAddLista('autorizantes', $aa->id_persona);
			}
			$ult['motivo_baja']=$raux;
		} else {
			$ult='notFound';
		}
		return $ult;
	}	
	
	
	public function actionRefreshConcepto($id_concepto=null) 
	{
		if (empty($id_concepto) || $id_concepto=='null') {return;}
		$ac=AccesosConceptos::findOne($id_concepto);
		\Yii::$app->session->set('req_seguro',$ac->req_seguro);
		// en la vista solo se usa el grupo personas pero se devuelve todo
		\Yii::$app->response->format = 'json';		
		$response=$this->refreshListas();
		return $response;		
	}
	
	public function actionPideComentario()
	{
		// solo se usa para mostrar el formulario que pide el comentario
		$comentario=\Yii::$app->session->get('comentario');
		
		return $this->renderAjax('_comentario', [
			'comentario' => $comentario,
		]);
	}	
	public function actionSetComentario($comentario=null) 
	{
		Yii::trace($comentario);
		if ($comentario=='null') {return 'Comentario';}
		\Yii::$app->session->set('comentario',$comentario);
		
		return $comentario==''?'Comentario':'<i class="glyphicon glyphicon-eye-open"></i> Comentario';		
	}	
	
	public function actionRefrescaListas() 
	{
		// solamente se usa para los grupos personas y vehiculos (se llama luego de ingresar un mensaje/comentario)
		\Yii::$app->response->format = 'json';		
		$response=$this->refreshListas();
		return $response;
	}


	public function refreshListas() {
		$response=['ingpersonas'=>'','ingvehiculos'=>'','autorizantes'=>'','egrpersonas'=>'','egrvehiculos'=>''];
		foreach ($response as $grupo=>$valor) {
		
			// Se recupera de la sesion
			$sess=\Yii::$app->session->get($grupo);			

			if (!empty($sess)) {
				// Se crea el array vacio para el dataprovider
				$dp=[];				
				// La session solo contiene los IDs, se recorre el array y se completa $dp con el objeto que corresponda
				foreach ($sess as $p) {
					switch ($grupo) {
						case 'ingpersonas':
							$dp[]=Personas::findOne($p);
							break;	
						case 'ingvehiculos':
							$dp[]=Vehiculos::findOne($p);
							break;							
						case 'autorizantes':
							$dp[]=Autorizantes::findOne($p);
							break;	
						case 'egrpersonas':
							$dp[]=Personas::findOne($p);
							break;	
						case 'egrvehiculos':
							$dp[]=Vehiculos::findOne($p);
							break;							

					}	
				}
				$dataProvider = new ArrayDataProvider(['allModels'=>$dp]);			
			} else {
				// dataProvider vacio
				//return '';
				$response[$grupo]='';
				continue;
			} // if !empty $sess
			switch ($grupo) {
				case 'ingpersonas':
					$columns=[
						[
							'header'=>'<span class="glyphicon glyphicon-trash"></span>',
							'attribute'=>'Acción',
							'format' => 'raw',
							'value' => function ($model, $index, $widget) {
													$url=Yii::$app->urlManager->createUrl(
														['accesos/drop-lista',
														'grupo'=>'ingpersonas', 
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
																			$("#divlistapersonas").html(r["ingpersonas"]);
																		}
														});return false;',
														]);			
										},
						],
						[
							'header'=>'<span class="glyphicon glyphicon-envelope"></span>',
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

						[
							'attribute'=>'vto_seguro',
							'visible'=>\Yii::$app->session->get('req_seguro'),
							'format' => 'raw',
							'value' => function ($model, $index, $widget) {
								
											if (empty($model->vto_seguro)) {
												$pide=true;
											} else {
												// se debe controlar si no está vencido el seguro
												if ($this->fecVencida($model->vto_seguro)) {												
													$pide=true;
												} else {
													// no está vencido, por lo tanto no se pide, solo se muestra
													$pide=false;
												}
											}
								
											if (!$pide) {
												return Yii::$app->formatter->format($model->vto_seguro, 'date'); 
											} else {
												$url=Yii::$app->urlManager->createUrl(
														['accesos/pide-seguro','idPersona'=>$model->id,]);							
												return Html::a(empty($model->vto_seguro)?'Sin seguro':
														Yii::$app->formatter->format($model->vto_seguro,'date'), 
													$url,
													['title' => 'Modificar fecha de vencimiento',
													 'onclick'=>'$.ajax({
														type     :"POST",
														cache    : false,
														url  : $(this).attr("href"),
														success  : function(response) {
																console.log(response);
																$("#divupdseguro").html(response);
																$("#modalupdseguro").modal("show");

																	}
													});return false;',
													]);			
											}
									},							
						],
						'id',
						'apellido',
						'nombre',
						'nombre2',
						'nro_doc',					
					];
					$heading='<i class="glyphicon glyphicon-user"></i>  Personas (Ingreso)';
					//$heading='Personas';
					break;	
				case 'egrpersonas':
					$columns=[
						[
							'header'=>'<span class="glyphicon glyphicon-trash"></span>',
							'attribute'=>'Acción',
							'format' => 'raw',
							'value' => function ($model, $index, $widget) {
													$url=Yii::$app->urlManager->createUrl(
														['accesos/drop-lista',
														'grupo'=>'egrpersonas', 
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
																			$("#divlistapersonas").html(r["egrpersonas"]);
																		}
														});return false;',
														]);			
										},
						],
						[
							'header'=>'<span class="glyphicon glyphicon-envelope"></span>',
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
						[
							'header'=>'',
							'attribute'=>'Sin ingreso',
							'format' => 'raw',
							'value' => function ($model, $index, $widget) {
													$a=Accesos::find()
														->where(['id_persona'=>$model->id,'egr_fecha'=>null])
														->orderBy(['id' => SORT_DESC])->one();
													if (empty($a)) {
														return '<span class="glyphicon glyphicon-bell" 
															title="Sin ingreso" style="color:#FF8000">
															</span>';
													} else {
														return '';
													}		
										},
						],
						'id',
						'apellido',
						'nombre',
						'nombre2',
						'nro_doc',					
					];
					$heading='<i class="glyphicon glyphicon-user"></i>  Personas (Egreso)';
					//$heading='Personas';
					break;	
				case 'ingvehiculos':
					$columns=[
						[
							'header'=>'<span class="glyphicon glyphicon-trash"></span>',
							'attribute'=>'Acción',
							'format' => 'raw',
							'value' => function ($model, $index, $widget) {
													$url=Yii::$app->urlManager->createUrl(
														['accesos/drop-lista',
														'grupo'=>'ingvehiculos', 
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
																			$("#divlistavehiculos").html(r["ingvehiculos"]);
																		}
														});return false;',
														]);			
										},
						],
						[
							'header'=>'<span class="glyphicon glyphicon-envelope"></span>',
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
						'id',
						'patente',
						'marca',
						'modelo',
						'color',
					

					];
					$heading='<i class="fa fa-car"></i>  Vehiculos (Ingreso)';
					//$heading='Vehiculos';
					break;			
				case 'egrvehiculos':
					$columns=[
						[
							'header'=>'<span class="glyphicon glyphicon-trash"></span>',
							'attribute'=>'Acción',
							'format' => 'raw',
							'value' => function ($model, $index, $widget) {
													$url=Yii::$app->urlManager->createUrl(
														['accesos/drop-lista',
														'grupo'=>'egrvehiculos', 
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
																			$("#divlistavehiculos").html(r["egrvehiculos"]);
																		}
														});return false;',
														]);			
										},
						],
						[
							'header'=>'<span class="glyphicon glyphicon-envelope"></span>',
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
						'id',
						'patente',
						'marca',
						'modelo',
						'color',
					

					];
					$heading='<i class="fa fa-car"></i>  Vehiculos (Egreso)';
					//$heading='Vehiculos';
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
															success  : function(r) {
																			$("#divlistaautorizantes").html(r["autorizantes"]);
																		}
														});return false;',
														]);			
										},
						],
						'persona.apellido',
						'persona.nombre',
						'persona.nombre2',
						'persona.nro_doc',
						'id_uf',
					];

					$heading='<i class="fa fa-key"></i>  Autorizantes (Ingreso)';						
					//$heading='Autorizantes';			
					break;									
			}			
			
			if ($grupo=='egrpersonas' || $grupo=='egrvehiculos') {
				$gvType=GridView::TYPE_DANGER;
			} else {
				$gvType=GridView::TYPE_INFO;
			}
			 
			 
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
	
	public function fecVencida($fec) 
	{
		$hoy = strtotime(date('Y-m-d'));
		$vto = strtotime($fec);
		return ($vto >= $hoy)?false:true;
	}

    public function actionEgreso()
    {
		// chequea que se haya elegido un porton, sino es asi se redirecciona a la eleccion de porton
		if (!\Yii::$app->session->get('porton')) {
			// se setea returnUrl para que funcione el goBack en portones/elegir (parecido a lo que hace login())
			Yii::$app->user->setReturnUrl(Yii::$app->urlManager->createUrl(['accesos/egreso']));
			return $this->redirect(['portones/elegir']);
		}
		
		// inicializa modelo
        $model = new Accesos();
 
		// si viene por POST, es decir, si se intenta grabar
		if (isset($_POST['Accesos'])) {
			$model->attributes = $_POST['Accesos'];
			// recupera de la sesion los 2 grupos
			$sessPersonas=\Yii::$app->session->get('egrpersonas');	
			$sessVehiculo=\Yii::$app->session->get('egrvehiculos');

			// se verifica que estén los 2 grupos cargados
			$rechaza=false;
			if (!$sessPersonas) {
				\Yii::$app->session->addFlash('danger','Debe especificar al menos una persona');
				$rechaza=true;
			}
			if (!$sessVehiculo) {
				\Yii::$app->session->addFlash('danger','Debe especificar un vehiculo');
				$rechaza=true;
			}
			
			
			if ($rechaza) {
				// actualiza los 2 grupos en variables que se van a pasar al render
				// si se modifica, modificar tambien antes del render del final de la funcion
				$listas=$this->refreshListas();
				return $this->render('egreso', [
					'model' => $model,
					'tmpListas'=>$listas,
				]);   				
			}			

			// Para que coincidan las fechas y horas en todos los registros se utilizan variables auxiliares antes de grabar
			$fecAux=date("Y-m-d");
			$horAux=new Expression('CURRENT_TIMESTAMP');
			// Comienza Transaccion
			$transaction = Yii::$app->db->beginTransaction();				
			try {
				foreach ($sessPersonas as $id_persona) {
					$model=Accesos::find()->where(['id_persona'=>$id_persona,'egr_fecha'=>null])
						->orderBy(['id' => SORT_DESC])->one();
					if (!empty($model)) {	
						foreach ($sessVehiculo as $model->egr_id_vehiculo) {
							// Aunque deberia haber un solo vehiculo
							
							// Para que save() no funcione como update sino como insert, 
							// se debe resetear el id y setear isNewRecord como true
							//$model->id = null;
							$model->egr_fecha=$fecAux;
							$model->egr_hora=$horAux;
							$model->egr_id_porton=\Yii::$app->session->get('porton');        
							$model->egr_id_user=\Yii::$app->user->identity->id;					
							//$model->isNewRecord = true;
							if ($model->save()) {
								$c=\Yii::$app->session->get('comentario');
								if ($c !== '') {
									$com = new Comentarios();
									$com->model=Accesos::className();
									$com->model_id=$model->id;
									$com->comentario=$c;
									$com->save();								
								}    
							}
						}
					} else {		
						$model=new Accesos();
						foreach ($sessVehiculo as $model->egr_id_vehiculo) {
							// Aunque deberia haber un solo vehiculo
							
							// Para que save() no funcione como update sino como insert, 
							// se debe resetear el id y setear isNewRecord como true
							$model->id = null;
							$model->id_persona=$id_persona;
							$model->ing_id_vehiculo=$model->egr_id_vehiculo;
							$model->ing_fecha=$fecAux;
							$model->ing_hora=$horAux;
							$model->egr_fecha=$fecAux;
							$model->egr_hora=$horAux;
							$model->egr_id_porton=\Yii::$app->session->get('porton');  
							$model->ing_id_porton=$model->egr_id_porton;      
							$model->egr_id_user=\Yii::$app->user->identity->id;					
							$model->ing_id_user=\Yii::$app->user->identity->id;		
							$model->id_concepto=0;
							$model->motivo='Sin ingreso';
										
							$model->isNewRecord = true;
							if ($model->save()) {
								$c=\Yii::$app->session->get('comentario');
								if ($c !== '') {
									$com = new Comentarios();
									$com->model=Accesos::className();
									$com->model_id=$model->id;
									$com->comentario=$c;
									$com->save();								
								}    
							}
						}

					} //foreach vehiculos
					
				} //foreach personas
				
				// Todo bien
				$transaction->commit();
				\Yii::$app->session->set('comentario','');
				\Yii::$app->session->addFlash('success','Egreso grabado correctamente');
				
				// limpia todo
				\Yii::$app->session->remove('egrpersonas');							
				\Yii::$app->session->remove('egrvehiculos');							
				
				return $this->redirect(['egreso']);
				

			} catch(\Exception $e) {
				$transaction->rollBack();
				Yii::$app->session->addFlash('danger','Hubo un error en la grabación');
				throw $e;
			} // try..catch
			

		} // if POST		
		
        // actualiza los 2 grupos en un array que se va a pasar al render
		// si se modifica, modificar tambien dentro del if (rechaza)
		$listas=$this->refreshListas();
		return $this->render('egreso', [
			'model' => $model,
			'tmpListas'=>$listas,
		]);        
		        
	}

    public function actionIngreso()
    {
		// chequea que se haya elegido un porton, sino es asi se redirecciona a la eleccion de porton
		if (!\Yii::$app->session->get('porton')) {
			// se setea returnUrl para que funcione el goBack en portones/elegir (parecido a lo que hace login())
			Yii::$app->user->setReturnUrl(Yii::$app->urlManager->createUrl(['accesos/ingreso']));
			return $this->redirect(['portones/elegir']);
		}		

		// inicializa modelo
        $model = new Accesos();
		\Yii::$app->session->set('req_seguro',0);    
	    
 
		// si viene por POST, es decir, si se intenta grabar
		if (isset($_POST['Accesos'])) {
			$model->attributes = $_POST['Accesos'];
			// setea la variable req_seguro de la sesion de acuerdo al valor del concepto que viene en el POST
			\Yii::$app->session->set('req_seguro',$model->accesosConcepto->req_seguro);			
			// recupera de la sesion los 3 grupos
			$sessPersonas=\Yii::$app->session->get('ingpersonas');	
			$sessVehiculo=\Yii::$app->session->get('ingvehiculos');
			$sessAutorizantes=\Yii::$app->session->get('autorizantes');

			// se verifica que estén los 3 grupos cargados
			$rechaza=false;
			if (!$sessPersonas) {
				\Yii::$app->session->addFlash('danger','Debe especificar al menos una persona');
				$rechaza=true;
			}
			if (!$sessVehiculo) {
				\Yii::$app->session->addFlash('danger','Debe especificar un vehiculo');
				$rechaza=true;
			}
			if (!$sessAutorizantes) {
				\Yii::$app->session->addFlash('danger','Debe especificar al menos un autorizante');
				$rechaza=true;
			}
			
			
			if ($sessPersonas) {
				// verifica los vencimientos de los seguros
				if ($model->accesosConcepto->req_seguro) {
					foreach ($sessPersonas as $segIDpersona) {
						$ps=Personas::findOne($segIDpersona);
						if (empty($ps->vto_seguro)) { 
							\Yii::$app->session->addFlash('danger','Personas sin seguro');
							$rechaza=true;
							break;
						}
						if ($this->fecVencida($ps->vto_seguro)) {
							\Yii::$app->session->addFlash('danger','Personas con seguro vencido');
							$rechaza=true;
							break;
						}
					}
					
					
				}
			}
			
			if ($rechaza) {
				// actualiza los 3 grupos en variables que se van a pasar al render
				// si se modifica, modificar tambien antes del render del final de la funcion
				$listas=$this->refreshListas();
				return $this->render('ingreso', [
					'model' => $model,
					'tmpListas'=>$listas,
				]);   				
			}			

			// Para que coincidan las fechas y horas en todos los registros se utilizan variables auxiliares antes de grabar
			$fecAux=date("Y-m-d");
			$horAux=new Expression('CURRENT_TIMESTAMP');
			// Comienza Transaccion
			$transaction = Yii::$app->db->beginTransaction();				
			try {
				foreach ($sessPersonas as $model->id_persona) {
					foreach ($sessVehiculo as $model->ing_id_vehiculo) {
						// Aunque deberia haber un solo vehiculo
						
						// Para que save() no funcione como update sino como insert, 
						// se debe resetear el id y setear isNewRecord como true
						$model->id = null;
						$model->ing_fecha=$fecAux;
						$model->ing_hora=$horAux;
						$model->ing_id_porton=\Yii::$app->session->get('porton');        
						$model->ing_id_user=\Yii::$app->user->identity->id;					
						
						$model->isNewRecord = true;
						if ($model->save()) {
							foreach ($sessAutorizantes as $id_autorizante) {
								$accaut=new AccesosAutorizantes();
								$accaut->id_acceso=$model->id;
								$aut=Autorizantes::findOne($id_autorizante);
								$accaut->id_persona=$aut->id_persona;
								$accaut->id_uf=$aut->id_uf;
								$accaut->save();
							} // foreach autorizantes
							$c=\Yii::$app->session->get('comentario');
							if ($c !== '') {
						        $com = new Comentarios();
								$com->model=Accesos::className();
								$com->model_id=$model->id;
								$com->comentario=$c;
								$com->save();								
							}    


						}// if model->save()	
					} //foreach vehiculos
					
				} //foreach personas
				
				// Todo bien
				$transaction->commit();
				\Yii::$app->session->set('comentario','');
				\Yii::$app->session->addFlash('success','Ingreso grabado correctamente');
				
				// limpia todo
				\Yii::$app->session->remove('ingpersonas');							
				\Yii::$app->session->remove('ingvehiculos');							
				\Yii::$app->session->remove('autorizantes');							
				
				return $this->redirect(['ingreso']);
				

			} catch(\Exception $e) {
				$transaction->rollBack();
				Yii::$app->session->addFlash('danger','Hubo un error en la grabación');
				throw $e;
			} // try..catch
			

		} // if POST		
		
        // actualiza los 3 grupos en un array que se va a pasar al render
		// si se modifica, modificar tambien dentro del if (rechaza)
		$listas=$this->refreshListas();
		return $this->render('ingreso', [
			'model' => $model,
			'tmpListas'=>$listas,
		]);        
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
