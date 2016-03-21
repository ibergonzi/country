<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Accesos;

/**
 * AccesosSearch represents the model behind the search form about `\frontend\models\Accesos`.
 */
class AccesosSearch extends Accesos
{
	
	public $descUsuarioIng;
	public $descUsuarioEgr;	
	
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_persona', 'ing_id_vehiculo', 'ing_id_porton', 'ing_id_user', 'egr_id_vehiculo', 'egr_id_porton', 'egr_id_user', 'id_concepto', 'cant_acomp', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['ing_fecha', 'ing_hora', 'egr_fecha', 'egr_hora', 'motivo', 'created_at', 'updated_at', 'motivo_baja',
				'descUsuarioIng','descUsuarioEgr',], 'safe'],
        ];
    }
    
    
    public function attributeLabels() 
    {
		return array_merge(parent::attributeLabels(),
			['descUsuarioIng'=>'U.Ing.','descUsuarioEgr'=>'U.Egr.']);
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
									
        $query = Accesos::find()->joinWith(['userIngreso','userEgreso']);
        
         $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['defaultOrder' => ['created_at' => SORT_DESC,],
						// esta opcion se usa para que sea el campo que el usuario ordene, luego ordene siempre por el default
						// es decir, si el usuario ordena por persona, la lista viene ordenada por persona y created_at desc
					   'enableMultiSort'=>true,
					  ],             
        ]);
        
        // Agregado a mano, para que incluya el ordenamiento por los campos relacionados
        $dataProvider->sort->attributes['descUsuarioIng'] = [
            'asc' => ['uing.username' => SORT_ASC],
            'desc' => ['uing.username' => SORT_DESC],
        ];        
        $dataProvider->sort->attributes['descUsuarioEgr'] = [
            'asc' => ['uegr.username' => SORT_ASC],
            'desc' => ['uegr.username' => SORT_DESC],
        ];        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'accesos.id' => $this->id,
            'id_persona' => $this->id_persona,
            'ing_id_vehiculo' => $this->ing_id_vehiculo,
            'ing_fecha' => $this->ing_fecha,
            'ing_hora' => $this->ing_hora,
            'ing_id_porton' => $this->ing_id_porton,
            'ing_id_user' => $this->ing_id_user,
            'egr_id_vehiculo' => $this->egr_id_vehiculo,
            'egr_fecha' => $this->egr_fecha,
            'egr_hora' => $this->egr_hora,
            'egr_id_porton' => $this->egr_id_porton,
            'egr_id_user' => $this->egr_id_user,
            'id_concepto' => $this->id_concepto,
            'cant_acomp' => $this->cant_acomp,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'motivo', $this->motivo])
              ->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja])
			  ->andFilterWhere(['like', 'uing.username', $this->descUsuarioIng])             
			  ->andFilterWhere(['like', 'uegr.username', $this->descUsuarioEgr])             
              ;

        return $dataProvider;
    }
}
