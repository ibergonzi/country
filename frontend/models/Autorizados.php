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
            [['id_persona', 'id_autorizante', 'created_by', 'created_at', 'updated_by', 'updated_at', 'estado'], 'required'],
            [['id_persona', 'id_autorizante', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['fec_desde', 'fec_hasta', 'created_at', 'updated_at'], 'safe'],
            [['motivo_baja'], 'string', 'max' => 50],
            [['id_persona', 'id_autorizante', 'fec_desde', 'fec_hasta', 'estado'], 'unique', 'targetAttribute' => ['id_persona', 'id_autorizante', 'fec_desde', 'fec_hasta', 'estado'], 'message' => 'The combination of Id Persona, Id Autorizante, Fec Desde, Fec Hasta and Estado has already been taken.'],
            [['id_autorizante'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_autorizante' => 'id']],
            [['id_persona'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_persona' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_persona' => 'Id Persona',
            'id_autorizante' => 'Id Autorizante',
            'fec_desde' => 'Fec.Desde',
            'fec_hasta' => 'Fec.Hasta',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
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
    public function getAutorizante()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_autorizante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Personas::className(), ['id' => 'id_persona']);
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
