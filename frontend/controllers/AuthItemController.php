<?php

namespace frontend\controllers;

use Yii;
use frontend\models\AuthItem;
use frontend\models\AuthItemChild;
use frontend\models\AuthItemSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
// para aplicar RBAC
use yii\filters\AccessControl;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends Controller
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
                    'clon' => ['POST'],                    
                ],
            ],
            
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
         
                    [
                        'actions' => ['index','delete','update','create','clon'],
                        'allow' => true,
                        'roles' => ['accederRoles'], 
                    ],
 
                 ], // fin rules
            ], // fin access   
                       
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($name)
    {
        return $this->render('view', [
            'model' => $this->findModel($name),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();
        $model->type=1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionClon($name)
    {
        $model = $this->findModel($name);
        
		// Comienza Transaccion
		$transaction = Yii::$app->db->beginTransaction();				
		try {        
			$n = new AuthItem();
			$n->type = 1;
			$n->description = 'Reemplazar por la descripción del nuevo rol';
			$n->name = $name.rand(0,99);
			$n->save(false);
			
			foreach ($model->authItemRoles as $perm) {
				$p = new AuthItemChild();
				$p->parent = $n->name;
				$p->child = $perm->child;
				$p->save(false);
			}
			
	
			$transaction->commit();        
		} catch(\Exception $e) {
				$transaction->rollBack();
				Yii::$app->session->addFlash('danger','Hubo un error en la grabación');
				throw $e;
		} // try..catch

        return $this->redirect(['index']);
    }


    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($name)
    {
        $this->findModel($name)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
