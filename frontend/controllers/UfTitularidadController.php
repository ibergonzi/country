<?php

namespace frontend\controllers;

use Yii;

use frontend\models\Uf;
use frontend\models\UfTitularidad;
use frontend\models\UfTitularidadPersonas;
use frontend\models\Personas;
use frontend\models\UfTitularidadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ArrayDataProvider;
use kartik\grid\GridView;

use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * UfTitularidadController implements the CRUD actions for UfTitularidad model.
 */
class UfTitularidadController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UfTitularidad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UfTitularidadSearch();
        $dataProvider = $searchModel->search(true,Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UfTitularidad model.
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
     * Creates a new UfTitularidad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($uf)
    {
        $model = new UfTitularidad();
        $model->id_uf=$uf;
        
        $titPers=new UfTitularidadPersonas();
       

        if ($model->load(Yii::$app->request->post())) {
             if ($model->validate()) {
				 Yii::trace('entro');
				$titPers->load(Yii::$app->request->post());
				$sessPersonas=\Yii::$app->session->get('titpersonas');
				if (!$sessPersonas) {
					\Yii::$app->session->addFlash('danger','Debe especificar al menos una persona');
				} else {
					// Comienza Transaccion
					$transaction = Yii::$app->db->beginTransaction();				
					try {
						$UfModel = Uf::findOne($model->id_uf);
						$ultMovimTit=UfTitularidad::findOne($UfModel->ultUfTitularidad->id);
						$ultMovimTit->ultima=false;
						$ultMovimTit->save(false);
								
						$model->ultima=true;						
						$model->save(false);
						
						$titPers->uf_titularidad_id=$model->id;
						foreach ($sessPersonas as $titPers->id_persona) {
							$titPers->id = null;
							$titPers->isNewRecord = true;							
							$titPers->save(false);
						} // foreach sessPersonas
						
						// falta la eliminación/inserción en autorizantes
						// ver que pasa con la finalización de una cesion
						

						
						// Todo bien
						$transaction->commit();
						\Yii::$app->session->addFlash('success','Movimiento grabado correctamente');
						// limpia todo
						\Yii::$app->session->remove('titpersonas');							
						return $this->redirect(['view', 'id' => $model->id]);
					} catch(\Exception $e) {
						$transaction->rollBack();
						Yii::$app->session->addFlash('danger','Hubo un error en la grabación');
						throw $e;
					} // try..catch
				} // else !sessPersonas
			} // $model->validate
			
         } // $model->load
        
        
        
		$listas=$this->refreshListas();         
		return $this->render('create', [
			'model' => $model,
			'titPers'=> $titPers,
			'tmpListas'=>$listas,                
		]);        
    }
    
    public function actionAddLista($grupo, $id)
    {
		// $grupo es solamente 'titpersonas'
		
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
		// $grupo es solamente 'titpersonas'
		
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
		$response=['titpersonas'=>''];		
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
												['uf-titularidad/drop-lista',
												'grupo'=>'titpersonas', 
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
																	$("#divlistapersonas").html(r["titpersonas"]);
																}
												});return false;',
												]);			
								},
				],

				'id',
				'apellido',
				'nombre',
				'nombre2',
				'tipoDoc.desc_tipo_doc_abr',				
				'nro_doc',					
			];
			$heading='<i class="glyphicon glyphicon-user"></i>  Personas (Titularidad)';
		
			
			$gvType=GridView::TYPE_DANGER;

			 
			 
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
    
    
    
    

    /**
     * Updates an existing UfTitularidad model.
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
     * Deletes an existing UfTitularidad model.
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
     * Finds the UfTitularidad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UfTitularidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UfTitularidad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
