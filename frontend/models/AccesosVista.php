<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "accesos_vista".
 *
 * @property integer $id
 * @property integer $id_acceso
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
 * @property string $control
 * @property integer $cant_acomp
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 * @property integer $id_autorizante
 * @property integer $id_uf
 */
class AccesosVista extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accesos_vista';
    }
    
	// creado a mano para que gii pueda crear controller y views
	public static function primaryKey()
    {     
        return ['id'];   
    }    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','id_acceso', 'id_persona', 'ing_id_vehiculo', 'ing_id_porton', 'ing_id_user', 'egr_id_vehiculo', 'egr_id_porton', 'egr_id_user', 'id_concepto', 'cant_acomp', 'created_by', 'updated_by', 'estado', 'id_autorizante', 'id_uf'], 'integer'],
            [['id_persona', 'ing_id_vehiculo', 'ing_fecha', 'ing_hora', 'ing_id_porton', 'ing_id_user', 'id_concepto', 'motivo', 'created_by', 'created_at', 'updated_by', 'updated_at', 'id_autorizante', 'id_uf'], 'required'],
            [['ing_fecha', 'ing_hora', 'egr_fecha', 'egr_hora', 'created_at', 'updated_at'], 'safe'],
            [['motivo', 'motivo_baja'], 'string', 'max' => 50],
            [['control'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'id_acceso' => Yii::t('app', 'ID'),
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
            'control' => Yii::t('app', 'Control'),
            'cant_acomp' => Yii::t('app', 'Cant.Acomp.'),
            'created_by' => Yii::t('app', 'Usuario alta'),
            'created_at' => Yii::t('app', 'Fecha alta'),
            'updated_by' => Yii::t('app', 'Usuario modif.'),
            'updated_at' => Yii::t('app', 'Fecha modif.'),
            'estado' => Yii::t('app', 'Estado'),            
            'motivo_baja' => Yii::t('app', 'Motivo Baja'),
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.', 
            'id_autorizante' => Yii::t('app', 'Id Autorizante'),
            'id_uf' => Yii::t('app', 'Id Uf'),
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
    public function getEgrVehiculo()
    {
        return $this->hasOne(Vehiculos::className(), ['id' => 'egr_id_vehiculo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngVehiculo()
    {
        return $this->hasOne(Vehiculos::className(), ['id' => 'ing_id_vehiculo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_persona']);
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

    public function getUserIngreso()
    {
        return $this->hasOne(\common\models\User::className(), 
				['id' => 'ing_id_user'])->from(\common\models\User::tableName() . ' uing');
    }    
    
    public function getUserEgreso()
    {
        return $this->hasOne(\common\models\User::className(), 
				['id' => 'egr_id_user'])->from(\common\models\User::tableName() . ' uegr');
				
    }    	    
}
