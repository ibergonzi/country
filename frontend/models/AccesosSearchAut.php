<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Accesos;

/**
 * AccesosSearch represents the model behind the search form about `\frontend\models\Accesos`.
 */
 
// OJO aca, extiende AccesosVistaF y no Accesos 
class AccesosSearchAut extends AccesosVistaF
{
	public $fecdesde;
	public $fechasta;	
	public $exportColumns;
	
	
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_persona', 'ing_id_vehiculo', 'ing_id_porton', 'ing_id_user', 
				'egr_id_vehiculo', 'egr_id_porton', 'egr_id_user', 'id_concepto', 
				'cant_acomp', 'created_by', 'updated_by', 'estado','id_autorizante','id_uf'], 'integer'],
            [['ing_fecha', 
				//'ing_hora', 
				'egr_fecha', 
				//'egr_hora', 
				'motivo','control', 'created_at', 'updated_at', 'motivo_baja',
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
				'r_aut_apellido',
				'r_aut_nombre',
				'r_aut_nombre2',
				//'r_desc_tipo_doc',
				'r_aut_nro_doc',	
				'desc_concepto',
				'ing_id_llave',
				'egr_id_llave',										
				], 'safe'],
            [['fecdesde','fechasta',],'safe'],
            [['fecdesde','fechasta',],'validaRangoFechas','skipOnEmpty' => true],				
        ];
    }
    
    
    public function validaRangoFechas($attribute, $params) 
    {
		if (empty($this->fecdesde) || empty($this->fechasta)) {
			if (empty($this->fecdesde)) {
				$this->addError('fecdesde','Debe ingresar un rango de fechas');return;
			}
			if (empty($this->fechasta)) {
				$this->addError('fechasta','Debe ingresar un rango de fechas');return;
			}
		}
		if (strtotime($this->fecdesde) > strtotime($this->fechasta)) {
			$this->addError('fechasta','Esta fecha no puede ser anterior a la otra fecha');return;
		}
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
    public function search($params,$consDentro=null)
    {
		// OJO uso AccesosSearch para que me tome los attributelabels de las propiedades nuevas (antes tenia Accesos)


		if ($consDentro) {
			$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['accesos.defaultPageSize'];			
			$query = AccesosSearchAut::find()->andWhere(['egr_fecha'=>null,'estado'=>1]);
		} else {
			$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['accesosAut.defaultPageSize'];			
			$query = AccesosSearchAut::find();
		}

      
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
				'pageSize' => $pageSize,
			],
			'sort' => ['defaultOrder' => ['ing_hora'=>SORT_DESC],
						// esta opcion se usa para que sea el campo que el usuario ordene, luego ordene siempre por el default
						// es decir, si el usuario ordena por persona, la lista viene ordenada por persona y created_at desc
					   'enableMultiSort'=>true,
					  ],             
        ]);
        
 
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

		$query->andFilterWhere([
            'id_acceso' => $this->id_acceso,
            'id_persona' => $this->id_persona,
            'ing_id_vehiculo' => $this->ing_id_vehiculo,
            'ing_fecha' => $this->fecSP2EN($this->ing_fecha),
            'ing_hora' => $this->ing_hora,
            'ing_id_porton' => $this->ing_id_porton,
            'ing_id_user' => $this->ing_id_user,
            'egr_id_vehiculo' => $this->egr_id_vehiculo,
            'egr_fecha' => $this->fecSP2EN($this->egr_fecha),
            'egr_hora' => $this->egr_hora,
            'egr_id_porton' => $this->egr_id_porton,
            'egr_id_user' => $this->egr_id_user,
            'ing_id_llave' => $this->ing_id_llave,
            'egr_id_llave' => $this->egr_id_llave,            
            'id_concepto' => $this->id_concepto,
            'cant_acomp' => $this->cant_acomp,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'estado' => $this->estado,
            'id_autorizante' => $this->id_autorizante,
            'id_uf' => $this->id_uf,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'motivo', $this->motivo])
            ->andFilterWhere(['like', 'control', $this->control])
            ->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja])
            ->andFilterWhere(['like', 'r_ing_usuario', $this->r_ing_usuario])
            ->andFilterWhere(['like', 'r_egr_usuario', $this->r_egr_usuario])
            ->andFilterWhere(['like', 'r_apellido', $this->r_apellido])
            ->andFilterWhere(['like', 'r_nombre', $this->r_nombre])
            ->andFilterWhere(['like', 'r_nombre2', $this->r_nombre2])
            ->andFilterWhere(['like', 'r_nro_doc', $this->r_nro_doc])
            ->andFilterWhere(['like', 'r_aut_apellido', $this->r_aut_apellido])
            ->andFilterWhere(['like', 'r_aut_nombre', $this->r_aut_nombre])
            ->andFilterWhere(['like', 'r_aut_nombre2', $this->r_aut_nombre2])
            ->andFilterWhere(['like', 'r_aut_nro_doc', $this->r_aut_nro_doc])
            ->andFilterWhere(['like', 'r_ing_patente', $this->r_ing_patente])
            ->andFilterWhere(['like', 'r_ing_marca', $this->r_ing_marca])
            ->andFilterWhere(['like', 'r_ing_modelo', $this->r_ing_modelo])
            ->andFilterWhere(['like', 'r_ing_color', $this->r_ing_color])
            ->andFilterWhere(['like', 'r_egr_patente', $this->r_egr_patente])
            ->andFilterWhere(['like', 'r_egr_marca', $this->r_egr_marca])
            ->andFilterWhere(['like', 'r_egr_modelo', $this->r_egr_modelo])
            ->andFilterWhere(['like', 'r_egr_color', $this->r_egr_color])
            ->andFilterWhere(['like', 'desc_concepto', $this->desc_concepto]);

		if (isset($params['resetFechas'])) {
			\Yii::$app->session->remove('accesosFecDesdeF');
			\Yii::$app->session->remove('accesosFecHastaF');
			$this->fecdesde=null;
			$this->fechasta=null;
			unset($params['resetFechas']);
		}
		

        if (!empty($this->fecdesde) && !empty($this->fechasta)) {
			// cada vez que se envia el form con el rango de fechas se guardan las fechas en sesion
			\Yii::$app->session->set('accesosFecDesdeF',$this->fecdesde);			
			\Yii::$app->session->set('accesosFecHastaF',$this->fechasta);					
			
			// para el between entre datetimes se debe agregar un dia mas a la fecha hasta
			$f=new \DateTime($this->fechasta);
			//$f->add(new \DateInterval('P1D'));
			$query->andFilterWhere(['between', 'ing_fecha', $this->fecdesde, $f->format('Y-m-d')]);
			
		} else {
			$sfd=\Yii::$app->session->get('accesosFecDesdeF')?\Yii::$app->session->get('accesosFecDesdeF'):'';
			$sfh=\Yii::$app->session->get('accesosFecHastaF')?\Yii::$app->session->get('accesosFecHastaF'):'';
			
			// si todavia están en sesion las variables del rango de fechas se hace el between y se elimina created_at
			if ($sfd && $sfh) {
				// para el between entre datetimes se debe agregar un dia mas a la fecha hasta
				$f=new \DateTime(\Yii::$app->session->get('accesosFecHastaF'));
				//$f->add(new \DateInterval('P1D'));
				$query->andFilterWhere(['between', 'ing_fecha', \Yii::$app->session->get('accesosFecDesdeF'), 
						$f->format('Y-m-d')]);
				$this->created_at='';		
			}				
			else
			{	
				$query->andFilterWhere(['like', 'ing_fecha', $this->created_at]);
			}	
		}


        return $dataProvider;
    }
    
    public function fecSP2EN($fec) {
		if ($fec ==null) return null;
		$a=explode('/',$fec);
		$r=array_reverse($a);
		$f=implode('-',$r);		
		return $f;
    }
}
