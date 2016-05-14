<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Uf;
use frontend\models\UfSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use kartik\grid\GridView;
use frontend\models\UfTitularidadPersonas;

use yii\data\ActiveDataProvider;

/**
 * UfController implements the CRUD actions for Uf model.
 */
class UfController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        /*
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        */
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['borrarUf'], 
                    ],                
                    [
                        'actions' => ['index','view', 'titularidad'],
                        'allow' => true,
                        'roles' => ['accederListaUf'], 
                    ],
                    [
                        'actions' => ['create','update'],
                        'allow' => true,
                        'roles' => ['altaModificarUf'], 
                    ],
  		
                 ], // fin rules
             ], // fin access                 
        ];
    }
    
    
    public function actionTitularidad($id_uf)
    {
        $UfModel = Uf::findOne($id_uf);
        
       
        if (!empty($UfModel->ultUfTitularidad->id)) {
			$query=UfTitularidadPersonas::find()->joinWith('persona')
				->where(['uf_titularidad_id'=>$UfModel->ultUfTitularidad->id]);

			$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'sort' => ['defaultOrder' => ['tipo' => SORT_DESC,],
							'enableMultiSort'=>true,            
						  ],    
			]);					      
			
			$response=GridView::widget([
				'dataProvider' => $dataProvider,
				'condensed'=>true,
				'layout'=>'{items}',
				//opciones validas solo para el gridview de kartik
				'panel'=>[
					'type'=>GridView::TYPE_INFO,
					'heading'=>'Titularidad actual sobre U.F.'.$id_uf,
					//'headingOptions'=>['class'=>'panel-heading'],
					'footer'=>false,
					'before'=>false,
					'after'=>false,
				],		
				'panelHeadingTemplate'=>'{heading}',			
				'resizableColumns'=>false,					
				'columns' => [
					[
						'attribute'=>'tipo',
						'value'=>function ($model) {return UfTitularidadPersonas::getTipos($model->tipo);},
					],
					'id_persona',
					'persona.apellido',
					'persona.nombre',
					'persona.nombre2',
					'persona.tipoDoc.desc_tipo_doc_abr',
					'persona.nro_doc',
					//'observaciones',

				],
			]); 
		} else {
			$response='';
		}	
		\Yii::$app->response->format = 'json';				
		return $response;			
	}

    /**
     * Lists all Uf models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Uf model.
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
     * Creates a new Uf model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Uf();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Uf model.
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
     * Deletes an existing Uf model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		/*
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
        */
        
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
			$model->estado=Uf::ESTADO_BAJA;
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
     * Finds the Uf model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Uf the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Uf::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
