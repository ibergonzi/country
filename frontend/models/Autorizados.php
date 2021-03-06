<?php

namespace frontend\models;

use Yii;

// agregados por mi, auditoria
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "autorizados".
 *
 * @property integer $id
 * @property integer $id_persona
 * @property integer $id_autorizante
 * @property integer $id_uf
 * @property string $fec_desde
 * @property string $fec_hasta
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property Personas $idAutorizante
 * @property Personas $idPersona
 * @property Uf $idUf
 * @property AutorizadosHorarios[] $autorizadosHorarios
 */
class Autorizados extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'autorizados';
    }
    
    
    const ESTADO_BAJA = 0;
	const ESTADO_ACTIVO = 1;

	// funcion agregada a mano
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_ACTIVO=>'Activo',self::ESTADO_BAJA=>'Baja'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
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
            [['id_persona', 'id_autorizante', 'id_uf', ], 'required'],
            [['id_persona', 'id_autorizante', 'id_uf', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['fec_desde', 'fec_hasta', 'created_at', 'updated_at'], 'safe'],
            [['motivo_baja'], 'string', 'max' => 50],
           
            // Para que funcione el unique (NO he creado indice en la BD) era necesario poner las fechas
            // en 'default' es decir, cuando no se les carga valor se les asigna autom. NULL
 			[['fec_desde','fec_hasta',],'default'], 
 			/* 
            [['id_persona', 'id_autorizante', 'id_uf', 'fec_desde', 'fec_hasta', 'estado'], 'unique', 
				'targetAttribute' => ['id_persona', 'id_autorizante', 'id_uf', 'fec_desde', 'fec_hasta', 'estado'], 
				'message' => 'Duplicado'],
			*/
            [['id_persona', 'id_autorizante', 'id_uf', 'estado'], 'unique', 
				'targetAttribute' => ['id_persona', 'id_autorizante', 'id_uf', 'estado'], 
				'message' => 'Ya existe una autorización para la combinación Persona/Autorizante/U.F.'],
            //
            [['id_autorizante'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_autorizante' => 'id']],
            [['id_persona'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_persona' => 'id']],
            [['fec_desde','fec_hasta',],'validaRangoFechas','skipOnEmpty' => false], 
            [['id_uf'], 'exist', 'skipOnError' => true, 'targetClass' => Uf::className(), 'targetAttribute' => ['id_uf' => 'id']],
        ];
    }
    
    public function validaRangoFechas($attribute, $params) 
    {
		if (empty($this->fec_desde) && empty($this->fec_hasta)) {
			return;
		}		
		if (empty($this->fec_desde) || empty($this->fec_hasta)) {
			if (empty($this->fec_desde)) {
				$this->addError('fec_desde','Debe ingresar un rango de fechas');return;
			}
			if (empty($this->fec_hasta)) {
				$this->addError('fec_hasta','Debe ingresar un rango de fechas');return;
			}
		}
		if (strtotime($this->fec_desde) > strtotime($this->fec_hasta)) {
			$this->addError('fec_hasta','Esta fecha no puede ser anterior a la otra fecha');return;
		}
		if (strtotime($this->fec_desde) < strtotime(date('Y-m-d'))) {
			$this->addError('fec_desde','La fecha debe ser posterior al dia de hoy');return;
		}		
			
	}        

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_persona' => 'Persona',
            'id_autorizante' => 'Autorizante',
            'id_uf' => 'U.F.',
            'fec_desde' => 'Fec.desde',
            'fec_hasta' => 'Fec.hasta',
            'created_by' => 'Created By',
            'created_at' => 'Fecha alta',
            'updated_by' => 'Updated By',
            'updated_at' => 'Fecha modif.',
            'estado' => 'Estado',
            'motivo_baja' => 'Motivo Baja',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',
            'persApellido'=>'Apellido',
            'persNombre'=>'Nombre',
            'persNroDoc'=>'Nro.Doc.',
            'autApellido'=>'Apellido',
            'autNombre'=>'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutorizante()
    {
        return $this->hasOne(Personas::className(),
			['id' => 'id_autorizante'])->from(Personas::tableName() . ' a_persona');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Personas::className(),
			['id' => 'id_persona'])->from(Personas::tableName() . ' p_persona');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUf()
    {
        return $this->hasOne(Uf::className(), ['id' => 'id_uf']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutorizadosHorarios()
    {
        return $this->hasMany(AutorizadosHorarios::className(), ['id_autorizado' => 'id']);
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
