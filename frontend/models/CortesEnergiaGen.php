<?php

namespace frontend\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "cortes_energia_gen".
 *
 * @property integer $id
 * @property integer $id_cortes_energia
 * @property integer $id_generador
 * @property string $hora_desde
 * @property string $hora_hasta
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property CortesEnergia $idCortesEnergia
 * @property Generadores $idGenerador
 */
class CortesEnergiaGen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cortes_energia_gen';
    }

    const ESTADO_BAJA = 0;
	const ESTADO_ACTIVO = 1;
	
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_ACTIVO=>'Activo',self::ESTADO_BAJA=>'Baja'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
	}		
	
    public function behaviors()
    {
	  return [
		  [
			  'class' => BlameableBehavior::className(),
			  'createdByAttribute' => 'created_by',
			  'updatedByAttribute' => 'updated_by',
		  ],
		  [
			  'class' => TimestampBehavior::className(),
			  'createdAtAttribute' => 'created_at',
			  'updatedAtAttribute' => 'updated_at',                 
			  'value' => new Expression('CURRENT_TIMESTAMP')
		  ],

	  ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_cortes_energia', 'id_generador', 'hora_desde', 'hora_hasta', ], 'required'],
            [['id_cortes_energia', 'id_generador', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['hora_desde', 'hora_hasta', 'created_by', 'created_at', 'updated_by', 'updated_at','observaciones'], 'safe'],
            [['motivo_baja'], 'string', 'max' => 50],
            [['observaciones'], 'string', 'max' => 60],            
            [['id_cortes_energia'], 'exist', 'skipOnError' => true, 'targetClass' => CortesEnergia::className(), 'targetAttribute' => ['id_cortes_energia' => 'id']],
            [['id_generador'], 'exist', 'skipOnError' => true, 'targetClass' => Generadores::className(), 'targetAttribute' => ['id_generador' => 'id']],
            [['hora_desde','hora_hasta',],'validaRangoHoras','skipOnEmpty' => true], 
        ];
    }

    public function validaRangoHoras($attribute, $params) 
    {
		if (empty($this->hora_desde) || empty($this->hora_hasta)) {
			if (empty($this->hora_desde)) {
				$this->addError('hora_desde','Debe ingresar un rango de horas');return;
			}
			if (empty($this->hora_hasta)) {
				$this->addError('hora_hasta','Debe ingresar un rango de horas');return;
			}
		}
		if (strtotime($this->hora_desde) > strtotime($this->hora_hasta)) {
			$this->addError('hora_hasta','Esta hora no puede ser anterior a la otra hora');return;
		}
		// controla que el rango de horas esté dentro del rango del corte de energia
		$ce=CortesEnergia::findOne($this->id_cortes_energia);
		if (
			(strtotime($this->hora_desde) < strtotime($ce->hora_desde)) || 
			(strtotime($this->hora_desde) > strtotime($ce->hora_hasta))			
			) {
			$this->addError('hora_desde','Fuera de rango');return;
		}		
		if (
			(strtotime($this->hora_hasta) < strtotime($ce->hora_desde)) || 
			(strtotime($this->hora_hasta) > strtotime($ce->hora_hasta))			
			) {
			$this->addError('hora_hasta','Fuera de rango');return;
		}	
		// controla que el rango de horas no se superponga con otra novedad del mismo generador
		$ceg=self::find()->where(['estado'=>self::ESTADO_ACTIVO])->all();
		foreach ($ceg as $c) {
			// para el caso de una modificacion, evita que se controle a si misma
			if (!empty($this->id) && $this->id==$c->id) {continue;}
			
			if ($this->id_generador != $c->id_generador) {continue;}			
			
			if (
				(strtotime($this->hora_desde) >= strtotime($c->hora_desde)) && 
				(strtotime($this->hora_desde) <= strtotime($c->hora_hasta))			
				) {
				$this->addError('hora_desde','Se superpone con otra novedad');return;
			}		
			if (
				(strtotime($this->hora_hasta) >= strtotime($c->hora_desde)) && 
				(strtotime($this->hora_hasta) <= strtotime($c->hora_hasta))			
				) {
				$this->addError('hora_hasta','Se superpone con otra novedad');return;
			}				
		}		
	}


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_cortes_energia' => 'Id Cortes Energia',
            'id_generador' => 'Generador',
            'hora_desde' => 'Hora Desde',
            'hora_hasta' => 'Hora Hasta',
            'created_by' => 'Usuario alta',
            'created_at' => 'Fecha alta',
            'updated_by' => 'Usuario modif.',
            'updated_at' => 'Fecha modif.',
            'estado' => 'Estado',
            'motivo_baja' => 'Motivo Baja',
            'observaciones'=>'Observaciones',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',             
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCortesEnergia()
    {
        return $this->hasOne(CortesEnergia::className(), ['id' => 'id_cortes_energia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenerador()
    {
        return $this->hasOne(Generadores::className(), ['id' => 'id_generador']);
    }
    
    public function getUserCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), 
				['id' => 'created_by'])->from(\common\models\User::tableName() . ' ucre');
    }    
    
    public function getUserUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), 
				['id' => 'updated_by'])->from(\common\models\User::tableName() . ' uupd');
    }            
}
