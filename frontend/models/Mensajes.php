<?php

namespace frontend\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "mensajes".
 *
 * @property integer $id
 * @property string $avisar_a
 * @property string $mensaje
 * @property string $model
 * @property integer $model_id
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 */
class Mensajes extends \yii\db\ActiveRecord
{
    const ESTADO_BAJA = 0;
	const ESTADO_ACTIVO = 1;
	
	public static function getMensajesByModelId($modelName,$modelID)
    {
	    $ms=self::find()->where(['model_id'=>$modelID,'model'=>$modelName,'estado'=>self::ESTADO_ACTIVO])->one();
		return $ms;
	}	
	

	
	// funcion agregada a mano
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_ACTIVO=>'Activo',self::ESTADO_BAJA=>'Baja'];
	    if ($key !== null) {return $estados[$key];}
		return $estados;
	}		
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mensajes';
    }

	// se graban los strings en mayúsculas
    public function beforeSave($insert)
    {
			$this->avisar_a=mb_strtoupper($this->avisar_a);
			$this->mensaje=mb_strtoupper($this->mensaje);
 
            parent::beforeSave($insert);
            return true;
    } 

    // extiende los comportamientos de la clase Mensajes para grabar datos de auditoría
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
            [['avisar_a', 'mensaje', ], 'required'],
            [['model_id', 'created_by', 'updated_by', 'estado'], 'integer'],
            [['created_at', 'updated_at','model', 'model_id', 'created_by','updated_by'], 'safe'],
            [['avisar_a', 'model'], 'string', 'max' => 50],
            [['mensaje'], 'string', 'max' => 500],
			['estado','default','value'=>Mensajes::ESTADO_ACTIVO],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'avisar_a' => Yii::t('app', 'Avisar a'),
            'mensaje' => Yii::t('app', 'Mensaje'),
            'model' => Yii::t('app', 'Model'),
            'model_id' => Yii::t('app', 'Model ID'),
            'created_by' => Yii::t('app', 'Creado por'),
            'created_at' => Yii::t('app', 'Creado el'),
            'updated_by' => Yii::t('app', 'Modificado por'),
            'updated_at' => Yii::t('app', 'Modificado el'),
            'estado' => Yii::t('app', 'Estado'),
			'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modificación',             
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
