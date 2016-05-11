<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\InfracConceptos;

/**
 * InfracConceptosSearch represents the model behind the search form about `frontend\models\InfracConceptos`.
 */
class InfracConceptosSearch extends InfracConceptos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'es_multa', 'dias_verif', 'multa_unidad', 'multa_reincidencia', 'multa_reinc_dias', 'multa_personas', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['concepto', 'created_at', 'updated_at', 'motivo_baja'], 'safe'],
            [['multa_precio', 'multa_reinc_porc', 'multa_personas_precio'], 'number'],
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
        $query = InfracConceptos::find();
        
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['infracConceptos.defaultPageSize'];        

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],            
            'sort' => ['defaultOrder' => ['id' => SORT_ASC,],
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
            'es_multa' => $this->es_multa,
            'dias_verif' => $this->dias_verif,
            'multa_unidad' => $this->multa_unidad,
            'multa_precio' => $this->multa_precio,
            'multa_reincidencia' => $this->multa_reincidencia,
            'multa_reinc_porc' => $this->multa_reinc_porc,
            'multa_reinc_dias' => $this->multa_reinc_dias,
            'multa_personas' => $this->multa_personas,
            'multa_personas_precio' => $this->multa_personas_precio,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'concepto', $this->concepto])
            ->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja]);

        return $dataProvider;
    }
}
