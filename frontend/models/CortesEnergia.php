<?php

namespace frontend\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "cortes_energia".
 *
 * @property integer $id
 * @property string $hora_desde
 * @property string $hora_hasta
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $estado
 * @property string $motivo_baja
 *
 * @property CortesEnergiaGen[] $cortesEnergiaGens
 */
class CortesEnergia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cortes_energia';
    }	
	
    const ESTADO_BAJA = 0;
	const ESTADO_ACTIVO = 1;
	
	public static function getEstados($key=null)
	{
		$estados=[self::ESTADO_ACTIVO=>'Activo',self::ESTADO_BAJA=>'Baja'];
	    if ($key !== null) {
			return $estados[$key];
		}
		return $estados;
	}		
	
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
            [['hora_desde',], 'required'],
            [['hora_desde', 'hora_hasta',  'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'estado'], 'integer'],
            [['motivo_baja'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hora_desde' => 'Desde',
            'hora_hasta' => 'Hasta',
            'created_by' => 'Usuario alta',
            'created_at' => 'Fecha alta',
            'updated_by' => 'Usuario modif.',
            'updated_at' => 'Fecha modif.',
            'estado' => 'Estado',
            'motivo_baja' => 'Motivo Baja',
            'userCreatedBy.username'=>'Usuario alta',
            'userUpdatedBy.username'=>'Usuario modif.',             
        ];
    }
    
    public static function corteActivo() 
    {
		return self::find()->where(['hora_hasta'=>null,'estado'=>self::ESTADO_ACTIVO])->one();
	}
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCortesEnergiaGen()
    {
        return $this->hasMany(CortesEnergiaGen::className(), ['id_cortes_energia' => 'id']);
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
}
