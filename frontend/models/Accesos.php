<?php

namespace frontend\models;

use Yii;

use frontend\models\Personas;
use frontend\models\Vehiculos;


use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "accesos".
 *
 * @property integer $id
 * @property integer $id_persona
 * @property integer $ing_id_vehiculo
 * @property string $ing_fecha
 * @property string $ing_hora
 * @property integer $ing_id_porton
 * @property integer $ing_id_user
 * @property integer $egr_id_vehiculo
 * @property string $egr_fecha
 * @property string $egr_hora
 * @property integer $egr_id_porton
 * @property integer $egr_id_user
 * @property integer $id_concepto
 * @property string $motivo
 * @property integer $cant_acomp
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property AccesosConceptos $idConcepto
 * @property Vehiculos $egrIdVehiculo
 * @property Vehiculos $ingIdVehiculo
 * @property Personas $idPersona
 * @property AccesosAutorizantes[] $accesosAutorizantes
 * @property Personas[] $idAutorizantes
 * @property AccesosUf[] $accesosUfs
 */
class Accesos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accesos';
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
	
	
	// se graban los nombres en mayúsculas
    public function beforeSave($insert)
    {
			$this->motivo=mb_strtoupper($this->motivo);
 
            parent::beforeSave($insert);
            return true;
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
            [['id_persona', 'ing_id_vehiculo', 'ing_fecha', 'ing_hora', 
				'ing_id_porton', 'ing_id_user', 'id_concepto', 'motivo', ], 'required'],
            [['id_persona', 'ing_id_vehiculo', 'ing_id_porton', 
				'ing_id_user', 'egr_id_vehiculo', 'egr_id_porton', 'egr_id_user', 
				'id_concepto', 'cant_acomp', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['ing_fecha', 'ing_hora', 'egr_fecha', 'egr_hora', 
				'created_by','created_at', 'updated_at','updated_by'], 'safe'],
            [['motivo', 'motivo_baja'], 'string', 'max' => 50],
            ['cant_acomp','default','value'=>0],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_persona' => Yii::t('app', 'Persona'),
            'ing_id_vehiculo' => Yii::t('app', 'Vehic.Ing.'),
            'ing_fecha' => Yii::t('app', 'Fec.Ing.'),
            'ing_hora' => Yii::t('app', 'H.Ing.'),
            'ing_id_porton' => Yii::t('app', 'Porton Ing.'),
            'ing_id_user' => Yii::t('app', 'Usuario Ing.'),
            'egr_id_vehiculo' => Yii::t('app', 'Vehic.Egr.'),
            'egr_fecha' => Yii::t('app', 'Fec.Egr.'),
            'egr_hora' => Yii::t('app', 'H.Egr.'),
            'egr_id_porton' => Yii::t('app', 'Porton Egr.'),
            'egr_id_user' => Yii::t('app', 'Usuario Egr.'),
            'id_concepto' => Yii::t('app', 'Concepto'),
            'motivo' => Yii::t('app', 'Motivo'),
            'cant_acomp' => Yii::t('app', 'Cant.Acomp.'),
            'created_by' => Yii::t('app', 'Usuario alta'),
            'created_at' => Yii::t('app', 'Fecha alta'),
            'updated_by' => Yii::t('app', 'Usuario modif.'),
            'updated_at' => Yii::t('app', 'Fecha modif.'),
            'estado' => Yii::t('app', 'Estado'),            
            'motivo_baja' => Yii::t('app', 'Motivo Baja'),
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',             
            'userIngreso.username'=>'Usuario Ing.',
            'userEgreso.username'=>'Usuario Egr.',             

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesosConcepto()
    {
        return $this->hasOne(AccesosConceptos::className(), ['id' => 'id_concepto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgrIdVehiculo()
    {
        return $this->hasOne(Vehiculos::className(), ['id' => 'egr_id_vehiculo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngIdVehiculo()
    {
        return $this->hasOne(Vehiculos::className(), ['id' => 'ing_id_vehiculo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_persona']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesosAutorizantes()
    {
        return $this->hasMany(AccesosAutorizantes::className(), ['id_acceso' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAutorizantes()
    {
        return $this->hasMany(Personas::className(), ['id' => 'id_autorizante'])->viaTable('accesos_autorizantes', ['id_acceso' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesosUfs()
    {
        return $this->hasMany(AccesosUf::className(), ['id_acceso' => 'id']);
    }
    
    
    // Devuelve todos los vehiculos utilizados de una determinada persona (y que los vehiculos sigan activos)
    public static function getVehiculosPorPersona($id_persona) 
    {
		// se hace para verificar que exista el parametro pasado a esta funcion
		$p=Personas::findOne($id_persona);
		$command=\Yii::$app->db->createCommand('SELECT DISTINCT ing_id_vehiculo AS id_vehiculo,"" AS desc_vehiculo 
													FROM accesos LEFT JOIN vehiculos ON ing_id_vehiculo=vehiculos.id
													WHERE id_persona=:persona AND vehiculos.estado=1 
													ORDER BY ing_id_vehiculo DESC');
		$command->bindParam(':persona', $id_persona);
		$vehiculos=$command->queryAll();
		/*	
		foreach ($vehiculos as $vehiculo){
			echo $vehiculo['id_vehiculo'];
		}
		*/ 

		return $vehiculos;
	}
	
    // Devuelve todos los vehiculos utilizados de una determinada persona (y que los vehiculos sigan activos)
    public static function getPersonasPorVehiculo($id_vehiculo) 
    {
		// se hace para verificar que exista el parametro pasado a esta funcion
		$p=Vehiculos::findOne($id_vehiculo);
		$command=\Yii::$app->db->createCommand('SELECT DISTINCT id_persona AS id_persona,"" AS desc_persona 
													FROM accesos LEFT JOIN personas ON id_persona=personas.id
													WHERE ing_id_vehiculo=:vehiculo AND personas.estado=1 
													ORDER BY id_persona DESC');
		$command->bindParam(':vehiculo', $id_vehiculo);
		$personas=$command->queryAll();
		/*	
		foreach ($vehiculos as $vehiculo){
			echo $vehiculo['id_vehiculo'];
		}
		*/ 

		return $personas;
	}	
	
	
    public function getUserCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }    
    
    public function getUserUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }    	

    public function getUserIngreso()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'ing_id_user']);
    }    
    
    public function getUserEgreso()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'egr_id_user']);
    }    	


    /**
     * @inheritdoc
     * @return AccesosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccesosQuery(get_called_class());
    }
}
