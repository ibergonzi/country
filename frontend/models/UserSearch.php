<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
	public $descRolUsuario;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email','descRolUsuario'], 'safe'],
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
        $query = User::find()->joinWith('authAssignment.authItem');
		
		// Aca se cocina lo que deberia ver el usuario segun su rol

		$rol=User::getRol(Yii::$app->user->getId());

		switch($rol->name) {
			case (string)"intendente": 
				$query->andFilterWhere(['not in','item_name','intendente'])
								->andWhere(['not in','item_name','administrador'])
								->andWhere(['not in','item_name','consejo']);
				break;
			case (string)"administrador": 
				$query->andFilterWhere(['not in','item_name','administrador'])
								->andWhere(['not in','item_name','consejo']);
				break;
			case (string)"consejo": 
				$query->andFilterWhere(['not in','item_name','consejo']);

				break;
		}
		$query->andFilterWhere(['status' => User::STATUS_ACTIVE]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        // Agregado a mano, para que incluya el ordenamiento por descCliente
        $dataProvider->sort->attributes['descRolUsuario'] = [
            'asc' => ['auth_item.description' => SORT_ASC],
            'desc' => ['auth_item.description' => SORT_DESC],
        ];
        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            //->andFilterWhere(['like', 'auth_key', $this->auth_key])
            //->andFilterWhere(['like', 'password_hash', $this->password_hash])
            //->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'auth_item.description', $this->descRolUsuario]);

        return $dataProvider;
    }
}
