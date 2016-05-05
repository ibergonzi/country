<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Autorizantes;

/**
 * AutorizantesSearch represents the model behind the search form about `\frontend\models\Autorizantes`.
 */
class AutorizantesSearch extends Autorizantes
{
	public $apellido;
	public $nombre;
	public $nombre2;
	public $nro_doc;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_uf', 'id_persona'], 'integer'],
            [['apellido','nombre','nombre2','nro_doc'],'safe'],
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
        $query = Autorizantes::find()->joinWith('persona');
        
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['autorizantes.defaultPageSize'];        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],            
            'sort' => ['defaultOrder' => ['id_uf' => SORT_ASC,],
						'enableMultiSort'=>false,            
                      ],               
        ]);
        
        // Agregado a mano, para que incluya el ordenamiento por tipo de documento
        $dataProvider->sort->attributes['apellido'] = [
            'asc' => ['personas.apellido' => SORT_ASC],
            'desc' => ['personas.apellido' => SORT_DESC],
        ];   
        $dataProvider->sort->attributes['nombre'] = [
            'asc' => ['personas.nombre' => SORT_ASC],
            'desc' => ['personas.nombre' => SORT_DESC],
        ];       
        $dataProvider->sort->attributes['nombre2'] = [
            'asc' => ['personas.nombre2' => SORT_ASC],
            'desc' => ['personas.nombre2' => SORT_DESC],
        ];                    
        $dataProvider->sort->attributes['nro_doc'] = [
            'asc' => ['personas.nro_doc' => SORT_ASC],
            'desc' => ['personas.nro_doc' => SORT_DESC],
        ];     

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_uf' => $this->id_uf,
            'id_persona' => $this->id_persona,
        ]);
        
        $query->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'nombre2', $this->nombre2])
            ->andFilterWhere(['like', 'nro_doc', $this->nro_doc])

            ;        

        return $dataProvider;
    }
}
