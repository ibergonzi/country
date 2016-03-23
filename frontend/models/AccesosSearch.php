<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Accesos;

/**
 * AccesosSearch represents the model behind the search form about `\frontend\models\Accesos`.
 */
 
// OJO aca, extiende AccesosVista y no Accesos 
class AccesosSearch extends AccesosVista
{
	
	public $r_ing_usuario;
	public $r_egr_usuario;	
	public $r_apellido;
	public $r_nombre;
	public $r_nombre2;
	public $r_desc_tipo_doc;
	public $r_nro_doc;
	public $r_ing_patente;
	public $r_ing_marca;
	public $r_ing_modelo;
	public $r_ing_color;
	public $r_egr_patente;
	public $r_egr_marca;
	public $r_egr_modelo;
	public $r_egr_color;
	
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_persona', 'ing_id_vehiculo', 'ing_id_porton', 'ing_id_user', 'egr_id_vehiculo', 'egr_id_porton', 'egr_id_user', 'id_concepto', 'cant_acomp', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['ing_fecha', 'ing_hora', 'egr_fecha', 'egr_hora', 'motivo','control', 'created_at', 'updated_at', 'motivo_baja',
				'r_ing_usuario',
				'r_egr_usuario',
				'r_apellido',
				'r_nombre',
				'r_nombre2',
				//'r_desc_tipo_doc',
				'r_nro_doc',
				'r_ing_patente',
				'r_ing_marca',
				'r_ing_modelo',
				'r_ing_color',			
				'r_egr_patente',
				'r_egr_marca',
				'r_egr_modelo',
				'r_egr_color',			
				], 'safe'],
        ];
    }
    
    
    public function attributeLabels() 
    {
		return array_merge(parent::attributeLabels(),
			[
				'r_ing_usuario'=>'U.Ing.',
				'r_egr_usuario'=>'U.Egr.',
				'r_apellido'=>'Apell.',
				'r_nombre'=>'Nom.',
				'r_nombre2'=>'2 Nom.',
				'r_desc_tipo_doc'=>'T.Doc.',
				'r_nro_doc'=>'Nro.Doc.',
				'r_ing_patente'=>'Patente',
				'r_ing_marca'=>'Marca',
				'r_ing_modelo'=>'Modelo',
				'r_ing_color'=>'Color',				
				'r_egr_patente'=>'Patente',
				'r_egr_marca'=>'Marca',
				'r_egr_modelo'=>'Modelo',
				'r_egr_color'=>'Color',				
			]);
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
		// OJO uso AccesosSearch para que me tome los attributelabels de las propiedades nuevas (antes tenia Accesos)
        $query = AccesosSearch::find()->joinWith(['userIngreso','userEgreso','persona','ingVehiculo']);
        
         $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['defaultOrder' => ['created_at' => SORT_DESC,],
						// esta opcion se usa para que sea el campo que el usuario ordene, luego ordene siempre por el default
						// es decir, si el usuario ordena por persona, la lista viene ordenada por persona y created_at desc
					   'enableMultiSort'=>true,
					  ],             
        ]);
        
        // Agregado a mano, para que incluya el ordenamiento por los campos relacionados
        $dataProvider->sort->attributes['r_ing_usuario'] = [
            'asc' => ['uing.username' => SORT_ASC],
            'desc' => ['uing.username' => SORT_DESC],
        ];        
        $dataProvider->sort->attributes['r_egr_usuario'] = [
            'asc' => ['uegr.username' => SORT_ASC],
            'desc' => ['uegr.username' => SORT_DESC],
        ];        
        
        /*
				'r_apellido',
				'r_nombre',
				'r_nombre2',
				'r_desc_tipo_doc',
				'r_nro_doc',
				'r_ing_patente',
				'r_ing_marca',
				'r_ing_modelo',
				'r_ing_color',			
				'r_egr_patente',
				'r_egr_marca',
				'r_egr_modelo',
				'r_egr_color',		
		*/
					
        $dataProvider->sort->attributes['r_apellido'] = [
            'asc' => ['personas.apellido' => SORT_ASC],
            'desc' => ['personas.apellido' => SORT_DESC],
        ];        
        $dataProvider->sort->attributes['r_nombre'] = [
            'asc' => ['personas.nombre' => SORT_ASC],
            'desc' => ['personas.nombre' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_nombre2'] = [
            'asc' => ['personas.nombre2' => SORT_ASC],
            'desc' => ['personas.nombre2' => SORT_DESC],
        ];         
        
        
        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_acceso' => $this->id,
            'accesos_vista.id_persona' => $this->id_persona,
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
              ->andFilterWhere(['like', 'control', $this->motivo_baja])
              ->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja])
			  ->andFilterWhere(['like', 'uing.username', $this->r_ing_usuario])             
			  ->andFilterWhere(['like', 'uegr.username', $this->r_egr_usuario])             
			  ->andFilterWhere(['like', 'personas.apellido', $this->r_apellido])             
              ;

        return $dataProvider;
    }
}
