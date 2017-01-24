<?php

namespace frontend\models;

use Yii;

// agregados por mi, auditoria
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "autorizados_horarios".
 *
 * @property integer $id
 * @property integer $id_autorizado
 * @property integer $dia
 * @property string $hora_desde
 * @property string $hora_hasta
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property Autorizados $idAutorizado
 */
class AutorizadosHorarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'autorizados_horarios';
    }

	// los codigos coinciden con los valores que devuelve el weekday() de mysql
    const DIA_LUNES = 0;
	const DIA_MARTES = 1;
	const DIA_MIERCOLES = 2;
	const DIA_JUEVES = 3;
	const DIA_VIERNES = 4;
	const DIA_SABADO = 5;
	const DIA_DOMINGO = 6;

	// funcion agregada a mano
	public static function getDias($key=null)
	{
		$dias=[self::DIA_LUNES=>'Lunes',self::DIA_MARTES=>'Martes',
			   self::DIA_MIERCOLES=>'Miércoles',self::DIA_JUEVES=>'Jueves',
			   self::DIA_VIERNES=>'Viernes',self::DIA_SABADO=>'Sábado',
			   self::DIA_DOMINGO=>'Domingo',
		];
	    if ($key !== null) {
			return $dias[$key];
		}
		return $dias;
	}	



    // extiende los comportamientos de la clase para grabar datos de auditoría
    // agregado por mi
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
            [['id_autorizado', 'dia', 'hora_desde', 'hora_hasta', ], 'required'],
            [['id_autorizado', 'dia', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['hora_desde', 'hora_hasta', 'created_at', 'updated_at'], 'safe'],
            [['motivo_baja'], 'string', 'max' => 50],
            [['id_autorizado', 'dia', 'estado'], 'unique', 'targetAttribute' => ['id_autorizado', 'dia', 'estado'], 'message' => 'The combination of Id Autorizado, Dia and Estado has already been taken.'],
            [['id_autorizado'], 'exist', 'skipOnError' => true, 'targetClass' => Autorizados::className(), 'targetAttribute' => ['id_autorizado' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_autorizado' => 'Id Autorizado',
            'dia' => 'Dia',
            'hora_desde' => 'Hora Desde',
            'hora_hasta' => 'Hora Hasta',
            'created_by' => 'Created By',
            'created_at' => 'Create At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'estado' => 'Estado',
            'motivo_baja' => 'Motivo Baja',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAutorizado()
    {
        return $this->hasOne(Autorizados::className(), ['id' => 'id_autorizado']);
    }

	// agregado por mi
    public function getUserCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }    
    
	// agregado por mi    
    public function getUserUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }          

}
