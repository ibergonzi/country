<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Autorizantes;
use frontend\models\AutorizantesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AutorizantesController implements the CRUD actions for Autorizantes model.
 */
class AutorizantesController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['borrarAutorizante'], 
                    ],                
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['accederListaAutorizantes'], 
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['altaAutorizante'], 
                    ],
                    [ 
                        'actions' => ['apellidoslist'],
						'allow' => true,
						'roles' => ['accederIngreso','accederEgreso'],  
					],
 		
                 ], // fin rules
             ], // fin access                  
        ];
    }
    
	// funcion utilizada para los select2, devuelve json
	public function actionApellidoslist($q = null, $id = null) {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
	
		if (!is_null($q)) {
			if (is_numeric($q)) {
				//$sp='CALL autorizantes_busca_nrosdoc(:query)' ;	
				$sp='CALL autorizantes_busca_uf(:query)' ;			
			} else {	
				$q=str_replace(' ','%',$q);
				$sp='CALL autorizantes_busca_nombres(:query)' ;
			}
            $command = Yii::$app->db->createCommand($sp);
            $q=trim($q);
            $command->bindParam(":query", $q);
			
			$data = $command->queryAll();
			
			// el command devuelve un array de arrays con forma id=>n,text=>''
			// se recorre todo el array, se detecta el key id y con su valor se busca la persona
			// y se agrega a un nuevo array para despues ordenarlo por text y devolverlo 
			$aux=['id'=>'','text'=>''];
			foreach ($data as $cadauno) {				
				foreach($cadauno as $key=>$valor) {
					if ($key=='id') {
						$t=Autorizantes::formateaAutorizanteSelect2($valor,is_numeric($q));
						$aux[]=['id'=>$valor,'text'=>$t];
					}
				}
			}
			asort($aux);
		
			$out['results'] = array_values($aux);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => Autorizantes::formateaAutorizanteSelect2($id,false)];
		}
		return $out;
	}      

    /**
     * Lists all Autorizantes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AutorizantesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Autorizantes model.
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
     * Creates a new Autorizantes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Autorizantes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Autorizantes model.
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
     * Deletes an existing Autorizantes model.
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
     * Finds the Autorizantes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Autorizantes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Autorizantes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
