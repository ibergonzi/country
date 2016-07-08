<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Infracciones;

/**
 * InfraccionesSearch represents the model behind the search form about `frontend\models\Infracciones`.
 */
class InfraccionesSearch extends Infracciones
{
	
	public $rConcepto;
	public $rUnidad;
	public $fecdesde;
	public $fechasta;		
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_uf', 'id_vehiculo', 'id_persona', 'id_concepto', 'id_informante', 'notificado', 'verificado', 'multa_unidad', 'multa_pers_cant', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['fecha', 'hora', 'nro_acta', 'lugar', 'descripcion', 'fecha_verif', 
				'foto', 'created_at', 'updated_at', 'motivo_baja','rUnidad','rConcepto','fecdesde','fechasta'], 'safe'],
            [['multa_monto', 'multa_pers_monto', 'multa_pers_total', 'multa_total'], 'number'],
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
    public function search($params)
    {
        $query = Infracciones::find()->joinWith(['concepto','multaUnidad']);
        
		$pageSize=isset($_GET['per-page'])?$_GET['per-page']:\Yii::$app->params['infracciones.defaultPageSize'];         

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

        // Agregado a mano, para que incluya el ordenamiento por concepto y unidad
        $dataProvider->sort->attributes['rConcepto'] = [
            'asc' => ['infrac_conceptos.concepto' => SORT_ASC],
            'desc' => ['infrac_conceptos.concepto' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['rUnidad'] = [
            'asc' => ['infrac_unidades.unidad' => SORT_ASC],
            'desc' => ['infrac_unidades.unidad' => SORT_DESC],
        ];        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'infracciones.id' => $this->id,
            'id_uf' => $this->id_uf,
            'id_vehiculo' => $this->id_vehiculo,
            'id_persona' => $this->id_persona,
            'fecha' => $this->fecSP2EN($this->fecha),
            'hora' => $this->hora,
            'id_concepto' => $this->rConcepto, //$this->id_concepto,
            'id_informante' => $this->id_informante,
            'notificado' => $this->notificado,
            'fecha_verif' => $this->fecSP2EN($this->fecha_verif),
            'verificado' => $this->verificado,
            'multa_unidad' => $this->rUnidad, //$this->multa_unidad,
            'multa_monto' => str_replace(",", ".", $this->multa_monto),
            'multa_pers_cant' => $this->multa_pers_cant,
            'multa_pers_monto' => str_replace(",", ".", $this->multa_pers_monto),
            'multa_pers_total' => str_replace(",", ".", $this->multa_pers_total),
            'multa_total' => str_replace(",", ".", $this->multa_total),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'infracciones.estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'nro_acta', $this->nro_acta])
            ->andFilterWhere(['like', 'lugar', $this->lugar])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'motivo_baja', $this->motivo_baja]);
            
		if (isset($params['resetFechas'])) {
			\Yii::$app->session->remove('infracFecDesde');
			\Yii::$app->session->remove('infracFecHasta');
			$this->fecdesde=null;
			$this->fechasta=null;
			unset($params['resetFechas']);
		}            
        if (!empty($this->fecdesde) && !empty($this->fechasta)) {
			// cada vez que se envia el form con el rango de fechas se guardan las fechas en sesion
			\Yii::$app->session->set('infracFecDesde',$this->fecdesde);			
			\Yii::$app->session->set('infracFecHasta',$this->fechasta);					
			
			// para el between entre datetimes se debe agregar un dia mas a la fecha hasta
			$f=new \DateTime($this->fechasta);
			//$f->add(new \DateInterval('P1D'));
			$query->andFilterWhere(['between', 'fecha', $this->fecdesde, $f->format('Y-m-d')]);
			
		} else {
			$sfd=\Yii::$app->session->get('infracFecDesde')?\Yii::$app->session->get('infracFecDesde'):'';
			$sfh=\Yii::$app->session->get('infracFecHasta')?\Yii::$app->session->get('infracFecHasta'):'';
			
			// si todavia están en sesion las variables del rango de fechas se hace el between y se elimina created_at
			if ($sfd && $sfh) {
				// para el between entre datetimes se debe agregar un dia mas a la fecha hasta
				$f=new \DateTime(\Yii::$app->session->get('infracFecHasta'));
				//$f->add(new \DateInterval('P1D'));
				$query->andFilterWhere(['between', 'fecha', \Yii::$app->session->get('infracFecDesde'), 
						$f->format('Y-m-d')]);
				$this->fecha='';		
			}				
			else
			{	
				//$query->andFilterWhere(['like', 'fecha', $this->fecha]);
			}	
		}            

        return $dataProvider;
    }
    
    public function fecSP2EN($fec) {
		if ($fec ==null) return null;
		$a=explode('/',$fec);
		$r=array_reverse($a);
		$f=implode('-',$r);	
		Yii::trace($f);	
		return $f;
    }    
}
