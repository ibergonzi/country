<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "uf_titularidad_personas".
 *
 * @property integer $id
 * @property integer $uf_titularidad_id
 * @property integer $id_persona
 * @property string $tipo
 * @property string $observaciones
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property Personas $idPersona
 * @property UfTitularidad $ufTitularidad
 */
class UfTitularidadPersonas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uf_titularidad_personas';
    }
    
    const TIPO_TIT = 'T';
	const TIPO_CES = 'S';    
    const TIPO_CED = 'D';
	const TIPO_AUT = 'A';

	// funcion agregada a mano
	public static function getTipos($key=null)
	{
		$estados=[self::TIPO_TIT=>'Titular',self::TIPO_CES=>'Cesionario',self::TIPO_CED=>'Cedente',self::TIPO_AUT=>'Autorizante'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
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
            [['uf_titularidad_id', 'tipo', ], 'required'],
            [['uf_titularidad_id', 'id_persona', 'created_by', 'updated_by'], 'integer'],
            [['created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
            [['tipo'], 'string', 'max' => 1],
            [['observaciones'], 'string', 'max' => 60],
            [['id_persona', 'uf_titularidad_id'], 'unique', 'targetAttribute' => ['id_persona', 'uf_titularidad_id'], 'message' => 'The combination of Uf Titularidad ID and Id Persona has already been taken.'],
            [['id_persona'], 'exist', 'skipOnError' => true, 'targetClass' => Personas::className(), 'targetAttribute' => ['id_persona' => 'id']],
            [['uf_titularidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => UfTitularidad::className(), 'targetAttribute' => ['uf_titularidad_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uf_titularidad_id' => 'Uf Titularidad ID',
            'id_persona' => 'Persona',
            'tipo' => 'Tipo Titularidad',
            'observaciones' => 'Observ./Teléf.',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
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
    public function getUfTitularidad()
    {
        return $this->hasOne(UfTitularidad::className(), ['id' => 'uf_titularidad_id']);
    }
}
