<?php

namespace frontend\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "accesos_autmanual".
 *
 * @property integer $id
 * @property string $hora_desde
 * @property string $hora_hasta
 * @property string $estado
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class AccesosAutmanual extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accesos_autmanual';
    }
    
    const ESTADO_ABIERTO = 'A';
	const ESTADO_CERRADO = 'C';
	

	// funcion agregada a mano
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_ABIERTO=>'Abierto',self::ESTADO_CERRADO=>'Cerrado'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
	}	 
	
	public static function periodoManualActivo($hora)        
	{
		$periodos=self::findAll()->where(['estado'=>self::ESTADO_ABIERTO]);
		$permite=false;
		foreach ($periodos as $p) {
			if ( strtotime($hora) >= strtotime($p->hora_desde) && strtotime($hora) <= strtotime($p->hora_hasta) ) {
				$permite=true;
			}
		}
		return $permite;
	}

    // extiende los comportamientos de la clase Personas para grabar datos de auditoría
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
            [['hora_desde', 'hora_hasta', ], 'required'],
            [['hora_desde', 'hora_hasta', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['estado'], 'string', 'max' => 1],
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
		if (strtotime($this->hora_desde) > strtotime("now")) {
			$this->addError('hora_desde','Debe especificar una fecha/hora anterior a la actual');return;
		}
		if (strtotime($this->hora_hasta) > strtotime("now")) {
			$this->addError('hora_hasta','Debe especificar una fecha/hora anterior a la actual');return;
		}	
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hora_desde' => 'Hora Desde',
            'hora_hasta' => 'Hora Hasta',
            'created_by' => Yii::t('app', 'Usuario alta'),
            'created_at' => Yii::t('app', 'Fecha alta'),
            'updated_by' => Yii::t('app', 'Usuario modif.'),
            'updated_at' => Yii::t('app', 'Fecha modif.'),
            'estado' => 'Estado',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.', 
        ];
    }
    
    public function getUserCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }    
    
    public function getUserUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }             
}
