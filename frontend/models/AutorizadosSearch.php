<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Autorizados;

/**
 * AutorizadosSearch represents the model behind the search form about `frontend\models\Autorizados`.
 */
class AutorizadosSearch extends Autorizados
{
	public $persApellido;
	public $persNombre;
	public $persNroDoc;
	
	public $autApellido;
	public $autNombre;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_persona', 'id_autorizante', 'id_uf', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['fec_desde', 'fec_hasta', 'created_at', 'updated_at', 'motivo_baja',
            'persApellido','persNombre','persNroDoc','autApellido','autNombre'], 'safe'],
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
        $query = Autorizados::find()->joinWith(['persona','autorizante']);

		//$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['REEMPLAZAR.defaultPageSize'];
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:15;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],            
            'sort' => ['defaultOrder' => ['id_persona' => SORT_ASC,],
						'enableMultiSort'=>true,            
                      ],              
        ]);
        
        $dataProvider->sort->attributes['persApellido'] = [
            'asc' => ['p_persona.apellido' => SORT_ASC],
            'desc' => ['p_persona.apellido' => SORT_DESC],
        ];   
        $dataProvider->sort->attributes['persNombre'] = [
            'asc' => ['p_persona.nombre' => SORT_ASC],
            'desc' => ['p_persona.nombre' => SORT_DESC],
        ];       
        $dataProvider->sort->attributes['persNroDoc'] = [
            'asc' => ['p_persona.nro_doc' => SORT_ASC],
            'desc' => ['p_persona.nro_doc' => SORT_DESC],
        ];             
        $dataProvider->sort->attributes['autApellido'] = [
            'asc' => ['a_persona.apellido' => SORT_ASC],
            'desc' => ['a_persona.apellido' => SORT_DESC],
        ];   
        $dataProvider->sort->attributes['autNombre'] = [
            'asc' => ['a_persona.nombre' => SORT_ASC],
            'desc' => ['a_persona.nombre' => SORT_DESC],
        ];       



        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_persona' => $this->id_persona,
            'id_autorizante' => $this->id_autorizante,
            'id_uf' => $this->id_uf,
            'fec_desde' => $this->fec_desde,
            'fec_hasta' => $this->fec_hasta,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'autorizados.estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja])
			->andFilterWhere(['like','p_persona.apellido',$this->persApellido])
			->andFilterWhere(['like','p_persona.nombre',$this->persNombre])
			->andFilterWhere(['like','p_persona.tipo_doc',$this->persNroDoc])
			->andFilterWhere(['like','a_persona.apellido',$this->autApellido])
			->andFilterWhere(['like','a_persona.nombre',$this->autNombre])
        ;

        return $dataProvider;
    }
}
