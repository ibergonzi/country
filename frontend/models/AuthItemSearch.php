<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\AuthItem;

use common\models\User;

/**
 * AuthItemSearch represents the model behind the search form about `frontend\models\AuthItem`.
 */
class AuthItemSearch extends AuthItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'rule_name', 'data'], 'safe'],
            [['type', 'created_at', 'updated_at'], 'integer'],
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
        $query = AuthItem::find()->where(['type'=>1]);
        
		// Aca se cocina lo que deberia ver el usuario segun su rol
		$rol=User::getRol(Yii::$app->user->getId());
		// el administrador no puede ver al usuario consejo, el consejo puede ver a todos, 
		// el intendente no puede ver al consejo ni administrador
		switch($rol->name) {
			case (string)"administrador": 
				$query->andFilterWhere(['not in','name',['consejo']]);
				break;
			case (string)"consejo": 
				break;
			case (string)"intendente": 
				$query->andFilterWhere(['not in','name',['administrador','consejo']]);
				break;				
			default:
				$query->andFilterWhere(['not in','name',['intendente','administrador','consejo']]);
		}	        
		// el rol "sinRol" es especial, no se puede usar		
		$query->andFilterWhere(['not in','name',['sinRol']]);

		//$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['REEMPLAZAR.defaultPageSize'];
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:15;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],            
            'sort' => ['defaultOrder' => ['name' => SORT_ASC,],
						'enableMultiSort'=>true,            
                      ],              
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rule_name', $this->rule_name])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
