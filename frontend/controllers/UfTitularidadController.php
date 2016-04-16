<?php

namespace frontend\controllers;

use Yii;

use frontend\models\Uf;
use frontend\models\UfTitularidad;
use frontend\models\UfTitularidadPersonas;
use frontend\models\Personas;
use frontend\models\UfTitularidadSearch;
use frontend\models\Autorizantes;

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
                    'fin-cesion' => ['POST'],
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
				$titPers->load(Yii::$app->request->post());
				$sessPersonas=\Yii::$app->session->get('titpersonas');
				if (!$sessPersonas) {
					\Yii::$app->session->addFlash('danger','Debe especificar al menos una persona');
				} else {
					// Comienza Transaccion
					$transaction = Yii::$app->db->beginTransaction();				
					try {
						// busca la UF
						$UfModel = Uf::findOne($model->id_uf);
						// guarda el id del ultimo movimiento de titularidad
						$idUltTitularidad=$UfModel->ultUfTitularidad->id;
						// busca el ultimo movimiento de titularidad
						$ultMovimTit=UfTitularidad::findOne($idUltTitularidad);
						// actualizar el campo ultima en false para que no sea mas la ultima titularidad
						$ultMovimTit->ultima=false;
						$ultMovimTit->save(false);
						
						// ultima en true indica que es el ultimo movimiento de titularidad (el que se esta grabando en este momento)		
						$model->ultima=true;						
						$model->save(false);
						
						// elimina todos los autorizantes actuales de la unidad para reemplazarlos con los nuevos
						Autorizantes::deleteAll(['id_uf'=>$model->id_uf]);	
						$aut=new Autorizantes();
						$aut->id_uf=$model->id_uf;								
						
						// grabación de personas 
						$titPers->uf_titularidad_id=$model->id;
						if ($model->tipoMovim->cesion) {
							$titPers->tipo=UfTitularidadPersonas::TIPO_CES;							 
						}
								
						foreach ($sessPersonas as $titPers->id_persona) {
							// graba en UfTitularidadPersonas
							$titPers->id = null;
							$titPers->isNewRecord = true;							
							$titPers->save(false);
							
							// graba en Autorizantes
							$aut->id = null;
							$aut->isNewRecord = true;	
							$aut->id_persona=$titPers->id_persona;						
							$aut->save(false);
						} // foreach sessPersonas
						
						if ($model->tipoMovim->cesion) {
							// si es una cesion, las personas de $sessPersonas se grabaron como cesionarios,
							// entonces se debe grabar a los titulares originales como cedentes
							$ultTitulares=UfTitularidadPersonas::find()->where(['uf_titularidad_id'=>$idUltTitularidad])->all(); 
							foreach ($ultTitulares as $ut) {
								$titPers->id=null;
								$titPers->isNewRecord=true;
								$titPers->id_persona=$ut->id_persona;
								$titPers->tipo=UfTitularidadPersonas::TIPO_CED;
								$titPers->observaciones=null;
								$titPers->save(false);
							}
						}
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
    
    public function actionFinCesion($uf,$id) {
        $model = new UfTitularidad();
        $model->id_uf=$uf;
        $model->tipo_movim=10;
        
        $movActual=UfTitularidad::findOne($id);
        $titPers=UfTitularidadPersonas::find()->where(['uf_titularidad_id'=>$id])->all();
 		$transaction = Yii::$app->db->beginTransaction();	       
 		try {   
			$movActual->ultima=false;
			$movActual->save(false);
			
			$model->fec_desde=$movActual->fec_hasta;
			$model->fec_hasta=$model->fec_desde;
			$model->exp_telefono=$movActual->exp_telefono;
			$model->exp_direccion=$movActual->exp_direccion;			
			$model->exp_localidad=$movActual->exp_localidad;
			$model->exp_email=$movActual->exp_email;
			$model->ultima=true;
			$model->save(false);
			
			// elimina todos los autorizantes actuales de la unidad para reemplazarlos con los nuevos
			Autorizantes::deleteAll(['id_uf'=>$model->id_uf]);	
			$aut=new Autorizantes();
			$aut->id_uf=$model->id_uf;	
			
			// recorre todos los titulares y solo procesa los cedentes como nuevos titulares					
			foreach ($titPers as $tp) {
				if ($tp->tipo==UfTitularidadPersonas::TIPO_CED) {
					$titp=new UfTitularidadPersonas();
					$titp->uf_titularidad_id=$model->id;
					$titp->tipo=UfTitularidadPersonas::TIPO_TIT;
					$titp->id_persona=$tp->id_persona;
					$titp->save(false);
					
					$aut->id = null;
					$aut->isNewRecord = true;	
					$aut->id_persona=$titp->id_persona;						
					$aut->save(false);					
				}
				
			}
			$transaction->commit();
			\Yii::$app->session->addFlash('success','Movimiento grabado correctamente');
			// limpia todo
			\Yii::$app->session->remove('titpersonas');							
			//return $this->redirect(['view', 'id' => $model->id]);			
					
		} catch(\Exception $e) {
			$transaction->rollBack();
			Yii::$app->session->addFlash('danger','Hubo un error en la grabación');
			throw $e;
		} // try..catch      
   
        return $this->redirect(['view', 'id' => $model->id]);
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
