<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\UserRol;

/**
 * UserRolSearch represents the model behind the search form about `frontend\models\UserRol`.
 */
class UserRolSearch extends UserRol
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'email', 'item_name', 'description'], 'safe'],
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
		// Aca se cocina lo que deberia ver el usuario segun su rol
		$auth = Yii::$app->authManager;
		$roles=$auth->getRolesByUser(Yii::$app->user->getId());
		foreach ($roles as $rol) {
			// acá no hace nada, se hace asi porque siempre hay un solo rol por usuario
		}
		//var_dump($rol->name);die;
		switch($rol->name) {
			case (string)"intendente": 
				$query=UserRol::find()->andwhere(['not in','item_name','intendente'])
								->andWhere(['not in','item_name','administrador'])
								->andWhere(['not in','item_name','consejo']);
				break;
			case (string)"administrador": 
				$query=UserRol::find()->andwhere(['not in','item_name','administrador'])
								->andWhere(['not in','item_name','consejo']);			
				break;
			case (string)"consejo": 
				$query=UserRol::find()->andwhere(['not in','item_name','consejo']);	
				break;
		}
		$query=UserRol::find()->andwhere(['is','item_name',null]);	
		

		
		
        //$query = UserRol::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
