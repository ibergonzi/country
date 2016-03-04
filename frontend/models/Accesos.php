<?php

namespace frontend\models;

use Yii;

use frontend\models\Personas;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_persona', 'ing_id_vehiculo', 'ing_fecha', 'ing_hora', 'ing_id_porton', 'ing_id_user', 'id_concepto', 'motivo', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['id_persona', 'ing_id_vehiculo', 'ing_id_porton', 'ing_id_user', 'egr_id_vehiculo', 'egr_id_porton', 'egr_id_user', 'id_concepto', 'cant_acomp', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['ing_fecha', 'ing_hora', 'egr_fecha', 'egr_hora', 'created_at', 'updated_at'], 'safe'],
            [['motivo', 'motivo_baja'], 'string', 'max' => 50],
            [['ing_fecha'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_persona' => Yii::t('app', 'Id Persona'),
            'ing_id_vehiculo' => Yii::t('app', 'Ing Id Vehiculo'),
            'ing_fecha' => Yii::t('app', 'Ing Fecha'),
            'ing_hora' => Yii::t('app', 'Ing Hora'),
            'ing_id_porton' => Yii::t('app', 'Ing Id Porton'),
            'ing_id_user' => Yii::t('app', 'Ing Id User'),
            'egr_id_vehiculo' => Yii::t('app', 'Egr Id Vehiculo'),
            'egr_fecha' => Yii::t('app', 'Egr Fecha'),
            'egr_hora' => Yii::t('app', 'Egr Hora'),
            'egr_id_porton' => Yii::t('app', 'Egr Id Porton'),
            'egr_id_user' => Yii::t('app', 'Egr Id User'),
            'id_concepto' => Yii::t('app', 'Id Concepto'),
            'motivo' => Yii::t('app', 'Motivo'),
            'cant_acomp' => Yii::t('app', 'Cant Acomp'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'estado' => Yii::t('app', 'Estado'),
            'motivo_baja' => Yii::t('app', 'Motivo Baja'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdConcepto()
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
		$command=\Yii::$app->db->createCommand('SELECT DISTINCT ing_id_vehiculo AS id_vehiculo 
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

    /**
     * @inheritdoc
     * @return AccesosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccesosQuery(get_called_class());
    }
}
