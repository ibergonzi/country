<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\MensPersonas;

/**
 * MensPersonasSearch represents the model behind the search form about `frontend\models\MensPersonas`.
 */
class MensPersonasSearch extends MensPersonas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'model_id', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['avisar_a', 'mensaje', 'apellido', 'nombre', 'nombre2', 'nro_doc', 'usuario_crea', 'created_at', 'usuario_borra', 'updated_at'], 'safe'],
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
        $query = MensPersonas::find();

		//$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['REEMPLAZAR.defaultPageSize'];
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:15;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],            
            'sort' => ['defaultOrder' => ['id' => SORT_DESC,],
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
            'id' => $this->id,
            'model_id' => $this->model_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'avisar_a', $this->avisar_a])
            ->andFilterWhere(['like', 'mensaje', $this->mensaje])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'nombre2', $this->nombre2])
            ->andFilterWhere(['like', 'nro_doc', $this->nro_doc])
            ->andFilterWhere(['like', 'usuario_crea', $this->usuario_crea])
            ->andFilterWhere(['like', 'usuario_borra', $this->usuario_borra]);

        return $dataProvider;
    }
}
