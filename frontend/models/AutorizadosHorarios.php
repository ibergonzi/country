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
 * @property string $create_at
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
            [['id_autorizado', 'dia', 'hora_desde', 'hora_hasta', 'created_by', 'create_at', 'updated_by', 'updated_at', 'estado'], 'required'],
            [['id_autorizado', 'dia', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['hora_desde', 'hora_hasta', 'create_at', 'updated_at'], 'safe'],
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
            'create_at' => 'Create At',
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
