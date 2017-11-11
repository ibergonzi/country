<?php

namespace frontend\models;

use Yii;

// agregados por mi, auditoria
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "personas_licencias".
 *
 * @property integer $id
 * @property integer $id_persona
 * @property integer $id_tipos_licencia
 * @property string $vencimiento
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property Personas $idPersona
 * @property TiposLicencia $idTiposLicencia
 */
class PersonasLicencias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'personas_licencias';
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
            [['id_persona', 'id_tipos_licencia', 'vencimiento', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'required'],
            [['id_persona', 'id_tipos_licencia', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['vencimiento', 'created_at', 'updated_at'], 'safe'],
            [['motivo_baja'], 'string', 'max' => 50],
            [['id_persona', 'id_tipos_licencia', 'estado'], 'unique', 'targetAttribute' => ['id_persona', 'id_tipos_licencia', 'estado'], 'message' => 'The combination of Id Persona, Id Tipos Licencia and Estado has already been taken.'],
            [['id_persona'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_persona' => 'id']],
            [['id_tipos_licencia'], 'exist', 'skipOnError' => true, 'targetClass' => TiposLicencia::className(), 'targetAttribute' => ['id_tipos_licencia' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_persona' => 'Persona',
            'id_tipos_licencia' => 'Id Tipos Licencia',
            'vencimiento' => 'Vencimiento',
            'created_by' => Yii::t('app', 'Usuario alta'),
            'created_at' => Yii::t('app', 'Fecha alta'),
            'updated_by' => Yii::t('app', 'Usuario modif.'),
            'updated_at' => Yii::t('app', 'Fecha modif.'),
            'estado' => Yii::t('app', 'Estado'),            
            'motivo_baja' => Yii::t('app', 'Motivo Baja'),
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',
        ];
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
    public function getTiposLicencia()
    {
        return $this->hasOne(TiposLicencia::className(), ['id' => 'id_tipos_licencia']);
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
