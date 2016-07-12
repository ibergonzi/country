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
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email','descRolUsuario','acceso_externo'], 'safe'],
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
        
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['user.defaultPageSize'];        
		
		// Aca se cocina lo que deberia ver el usuario segun su rol
		$rol=User::getRol(Yii::$app->user->getId());
		// el administrador no puede ver al usuario consejo, el consejo puede ver a todos, 
		// el intendente no puede ver al consejo ni administrador
		switch($rol->name) {
			case (string)"administrador": 
				$query->andFilterWhere(['not in','item_name',['consejo']]);
				break;
			case (string)"consejo": 
				break;
			case (string)"intendente": 
				$query->andFilterWhere(['not in','item_name',['administrador','consejo']]);
				break;				
			default:
				$query->andFilterWhere(['not in','item_name',['intendente','administrador','consejo']]);
	
		}
		$query->andFilterWhere(['status' => User::STATUS_ACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			 ],
			 'sort' => ['defaultOrder' => ['id' => SORT_DESC,],
						// esta opcion se usa para que sea el campo que el usuario ordene, luego ordene siempre por el default
						// es decir, si el usuario ordena por persona, la lista viene ordenada por persona y created_at desc
					   'enableMultiSort'=>true,
					  ],                    
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
            'acceso_externo'=>$this->acceso_externo,
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
