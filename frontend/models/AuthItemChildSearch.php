<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\AuthItemChild;

use common\models\User;

/**
 * AuthItemChildSearch represents the model behind the search form about `frontend\models\AuthItemChild`.
 */
class AuthItemChildSearch extends AuthItemChild
{
    public $descRol;
    public $descPermiso;	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child','descRol','descPermiso'], 'safe'],
        ];
    }
    


    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AuthItemChild::find()->joinWith(['rol','permiso']);

		//$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['REEMPLAZAR.defaultPageSize'];
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:100;
		
		// Aca se cocina lo que deberia ver el usuario segun su rol
		$rol=User::getRol(Yii::$app->user->getId());
		// el administrador no puede ver al usuario consejo, el consejo puede ver a todos, 
		// el intendente no puede ver al consejo ni administrador
		switch($rol->name) {
			case (string)"administrador": 
				$query->andFilterWhere(['not in','parent',['consejo']]);
				break;
			case (string)"consejo": 
				break;
			case (string)"intendente": 
				$query->andFilterWhere(['not in','parent',['administrador','consejo']]);
				break;				
			default:
				$query->andFilterWhere(['not in','parent',['intendente','administrador','consejo']]);
	
		}		
		

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],            
            'sort' => ['defaultOrder' => ['descRol' => SORT_ASC,'descPermiso'=>SORT_ASC],
						'enableMultiSort'=>true,            
                      ],              
        ]);
        
        $dataProvider->sort->attributes['descRol'] = [
            'asc' => ['authitem_r.description' => SORT_ASC],
            'desc' => ['authitem_r.description' => SORT_DESC],
        ];   
        $dataProvider->sort->attributes['descPermiso'] = [
            'asc' => ['authitem_p.description' => SORT_ASC],
            'desc' => ['authitem_p.description' => SORT_DESC],        
		];
		
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere(['like', 'parent', $this->parent])
            ->andFilterWhere(['like', 'child', $this->child])
            ->andFilterWhere(['like','parent',$this->descRol])
            ->andFilterWhere(['like','authitem_p.description',$this->descPermiso])            
            ;

        return $dataProvider;
    }
}
