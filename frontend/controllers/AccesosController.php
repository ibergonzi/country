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
    
    public function actionBuscaVehiculos($id_persona)
    {
		// recupera los vehiculos utilizados por la persona en ingresos
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
		
		return $this->renderAjax('_ingvehiculos', [
			'vehiculos' => $aux,
			'seleccion' => $seleccion,
		]);
	}
	
	
    public function actionBuscaPersonas($id_vehiculo)
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

		
		return $this->renderAjax('_ingpersonas', [
			'personas' => $aux,
			'seleccion'=>$seleccion,
		]);
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
		return $this->refreshLista('personas');		
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
	
	
    public function actionBuscaUltIngreso($grupo, $id)
    {
		// $grupo puede ser 'personas' o 'vehiculos'
		
		if (empty($id)) {return;}
		if ($grupo !== 'personas' && $grupo !== 'vehiculos') {return;}
		
		if ($grupo=='personas') {
			$ult=Accesos::find()->where(['id_persona'=>$id])->orderBy(['id' => SORT_DESC])->asArray()->one();
		} else {
			$ult=Accesos::find()->where(['ing_id_vehiculo'=>$id])->orderBy(['id' => SORT_DESC])->asArray()->one();			
		}
		\Yii::$app->response->format = 'json';
		
		$a=Accesos::findOne($ult['id']);
		
		if (!empty($a)) {
			$r1='';
			$r2='';
			foreach ($a->accesosAutorizantes as $aa) {
				//$oaa=AccesosAutorizantes::find($aa->id_persona
				$raa=$this->actionAddLista('autorizantes', $aa->id_persona);
			}
			foreach ($a->accesosUfs as $au) {
				$raf=$this->actionAddLista('ufs', $au->id_uf);
			}
			$ult['motivo_baja']=['autorizantes'=>$raa,'ufs'=>$raf];
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
		$response=$this->refreshLista('personas');
		return $response;		
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
					case 'ufs':
						$dp[]=Uf::findOne($p);
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
				$heading='<i class="glyphicon glyphicon-user"></i>  Personas';
				//$heading='Personas';
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
				$heading='<i class="fa fa-car"></i>  Vehiculos';
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

				$heading='<i class="fa fa-key"></i>  Autorizantes';						
				//$heading='Autorizantes';			
				break;									
			case 'ufs':
				$columns=[
					[
						'header'=>'<span class="glyphicon glyphicon-trash"></span>',
						'attribute'=>'Acción',
						'format' => 'raw',
						'value' => function ($model, $index, $widget) {
												$url=Yii::$app->urlManager->createUrl(
													['accesos/drop-lista',
													'grupo'=>'ufs', 
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
																		$("#divlistaufs").html(response);
																	}
													});return false;',
													]);			
									},
					],
					'id',
				];

				$heading='<i class="glyphicon glyphicon-home"></i>  Unidades';						
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
		Yii::trace($response);
		return $response;
	
	}
	
	public function fecVencida($fec) 
	{
		$hoy = strtotime(date('Y-m-d'));
		$vto = strtotime($fec);
		return ($vto >= $hoy)?false:true;
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
			//$ac=AccesosConceptos::findOne($model->id_concepto);
			\Yii::$app->session->set('req_seguro',$model->accesosConcepto->req_seguro);			
			// recupera de la sesion los 4 grupos
			$sessPersonas=\Yii::$app->session->get('personas');	
			$sessVehiculo=\Yii::$app->session->get('vehiculos');
			$sessAutorizantes=\Yii::$app->session->get('autorizantes');
			$sessUFs=\Yii::$app->session->get('ufs');

			// se verifica que estén los 4 grupos cargados
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
			if (!$sessUFs) {
				\Yii::$app->session->addFlash('danger','Debe especificar al menos una UF');
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
				// actualiza los 4 grupos en variables que se van a pasar al render
				// si se modifica, modificar tambien antes del render del final de la funcion
				$tmpListaPersonas=$this->refreshLista('personas');
				$tmpListaVehiculos=$this->refreshLista('vehiculos');				
				$tmpListaAutorizantes=$this->refreshLista('autorizantes');        
				$tmpListaUFs=$this->refreshLista('ufs');        		
				
				// hace un render para que no se pierda los datos del modelo (en vez de redirect que limpia todo)
				return $this->render('ingreso', [
					'model' => $model,
					'tmpListaPersonas'=>$tmpListaPersonas,
					'tmpListaVehiculos'=>$tmpListaVehiculos,			
					'tmpListaAutorizantes'=>$tmpListaAutorizantes,	
					'tmpListaUFs'=>$tmpListaUFs,			
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
								$aut=new AccesosAutorizantes();
								$aut->id_acceso=$model->id;
								$aut->id_persona=$id_autorizante;
								$aut->save();
							} // foreach autorizantes
							foreach ($sessUFs as $id_uf) {
								$auf=new AccesosUf();
								$auf->id_acceso=$model->id;
								$auf->id_uf=$id_uf;
								$auf->save();
							} // foreach ufs
						

						}// if model->save()	
					} //foreach vehiculos
					
				} //foreach personas
				
				// Todo bien
				$transaction->commit();
				\Yii::$app->session->addFlash('success','Ingreso grabado correctamente');
				
				// limpia todo
				\Yii::$app->session->remove('personas');							
				\Yii::$app->session->remove('vehiculos');							
				\Yii::$app->session->remove('autorizantes');							
				\Yii::$app->session->remove('ufs');	
				
				return $this->redirect(['ingreso']);
				

			} catch(\Exception $e) {
				$transaction->rollBack();
				Yii::$app->session->addFlash('danger','Hubo un error en la grabación');
				throw $e;
			} // try..catch
			

		} // if POST		
		
        // actualiza los 4 grupos en variables que se van a pasar al render
		// si se modifica, modificar tambien dentro del if (rechaza)
		$tmpListaPersonas=$this->refreshLista('personas');
		$tmpListaVehiculos=$this->refreshLista('vehiculos');				
		$tmpListaAutorizantes=$this->refreshLista('autorizantes');        
		$tmpListaUFs=$this->refreshLista('ufs');        		
		return $this->render('ingreso', [
			'model' => $model,
			'tmpListaPersonas'=>$tmpListaPersonas,
			'tmpListaVehiculos'=>$tmpListaVehiculos,			
			'tmpListaAutorizantes'=>$tmpListaAutorizantes,			
			'tmpListaUFs'=>$tmpListaUFs,			
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
