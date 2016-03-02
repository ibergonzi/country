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

use yii\grid\GridView;

use yii\helpers\Html;



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
				];
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

				];
				break;							
		}			
		
		
		 
		$response=GridView::widget([
			'dataProvider' => $dataProvider,
			'layout'=>'{items}',
			'columns' => $columns,
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
		return $this->render('ingreso', [
			//'model' => $searchModel,
			'model' => $model,
			'tmpListaPersonas'=>$tmpListaPersonas,
			'tmpListaVehiculos'=>$tmpListaVehiculos,			
			
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
