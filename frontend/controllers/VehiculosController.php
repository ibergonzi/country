<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Vehiculos;
use frontend\models\VehiculosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use kartik\widgets\ActiveForm;

use frontend\models\Accesos;
use frontend\models\Personas;
use yii\data\ArrayDataProvider;

/**
 * VehiculosController implements the CRUD actions for Vehiculos model.
 */
class VehiculosController extends Controller
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
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['borrarVehiculo'], 
                    ],                
                    [
                        'actions' => ['index','view','lista-personas'],
                        'allow' => true,
                        'roles' => ['accederListaVehiculos'], 
                    ],
                    [
                        'actions' => ['create','update'],
                        'allow' => true,
                        'roles' => ['altaModificarVehiculo'], 
                    ],
                    [
                        'actions' => ['create-ajax'],
                        'allow' => true,
                        'roles' => ['altaVehiculoIngEgr'], 
                    ],                    
                    [ 
                        'actions' => ['vehiculoslist'],
						'allow' => true,
						'roles' => ['accederIngreso','accederEgreso'],  
					],
 		
                 ], // fin rules
             ], // fin access              
        ];
    }


    /**
     * Lists all Vehiculos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VehiculosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionListaPersonas($id_vehiculo)    
    {

		$personas=Accesos::getPersonasPorVehiculo($id_vehiculo,false);
		
		// Si la persona es nueva o nunca tuvo accesos devuelve una bandera para que no se muestre el modal
		if (count($personas)==0) {
			return 'notFound';
		}			
		$dp=[];				
		foreach ($personas as $per) {
			foreach ($per as $k=>$v) {
				
				//Yii::trace($personas);die;
				if ($k=='id_persona') {
					$dp[]=Personas::findOne($v);
				}
			}
		}	
		$dataProvider = new ArrayDataProvider(['allModels'=>$dp]);				
        return $this->renderAjax('personaslist', [
            'dataProvider' => $dataProvider,
        ]);					
	}    

    /**
     * Displays a single Vehiculos model.
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
     * Creates a new Vehiculos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vehiculos();
    	$model->estado=Vehiculos::ESTADO_ACTIVO;         

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionCreateAjax()
    {
        $model = new Vehiculos();
     	$model->estado=Vehiculos::ESTADO_ACTIVO;      

		// Al estar habilitado la validation ajax, $_POST['ajax'] viene seteado, si vino por el submit, esta variable no existe
		// Siempre se devuelve el validate
		if (isset($_POST['ajax'])) {
				Yii::$app->response->format = 'json';				
				$model->load(Yii::$app->request->post());
				return ActiveForm::validate($model);
		}
		
		// si no viene seteado $_POST['ajax'] se asume que se entro por el submit
		if ($model->load(Yii::$app->request->post()) ) {
				if ($model->save()) {
					Yii::$app->response->format = 'json';
					return [
						//'message' => 'Success!!!',
						'modelP'=>$model
					];				
				} else {
					Yii::$app->response->format = 'json';				
					return ActiveForm::validate($model);
			}
		}	
		return $this->renderAjax('createajax', [
				'model' => $model,
		 ]);
    }    
    
    

    /**
     * Updates an existing Vehiculos model.
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
     * Deletes an existing Vehiculos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		// delete original		
        //$this->findModel($id)->delete();
        //return $this->redirect(['index']);
        
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
			$model->estado=Vehiculos::ESTADO_BAJA;
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } else {
            return $this->render('delete', [
                'model' => $model,
            ]);
        }                
        
    }

    /**
     * Finds the Vehiculos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vehiculos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vehiculos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Vehiculo inexistente');
        }
    }
    
	// funcion utilizada para los select2, devuelve json
	public function actionVehiculoslist($q = null, $id = null) {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
	
		if (!is_null($q)) {
			$q=str_replace(' ','%',$q);
			$sp='CALL vehiculos_busca(:query)' ;
            $command = Yii::$app->db->createCommand($sp);
            $command->bindParam(":query", trim($q));
			
			$data = $command->queryAll();
			
			// el command devuelve un array de array con forma id=>n,text=>''
			// se recorre todo el array, se detecta el key id y con su valor se busca el vehiculo
			// y se agrega a un nuevo array para despues ordenarlo por text y devolverlo 
			$aux=['id'=>'','text'=>''];
			foreach ($data as $cadauno) {				
				foreach($cadauno as $key=>$valor) {
					if ($key=='id') {
						$t=Vehiculos::formateaVehiculoSelect2($valor);
						$aux[]=['id'=>$valor,'text'=>$t];
					}
				}
			}
			asort($aux);
		
			$out['results'] = array_values($aux);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => Vehiculos::formateaVehiculoSelect2($id)];
		}
		return $out;
	}    
    
}
