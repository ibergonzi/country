<?php

namespace frontend\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "personas".
 *
 * @property integer $id
 * @property integer $dni
 * @property string $apellido
 * @property string $nombre
 * @property string $nombre2
 * @property string $fecnac

 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property Entradas[] $entradas
 * @property Salidas[] $salidas
 */
class Persona extends \yii\db\ActiveRecord
{

	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'personas';
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
			  //'value' => new Expression('NOW()')
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
            [['dni', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','created_by','updated_by','fecnac',], 'safe'],
            [['apellido', 'nombre', 'nombre2'], 'string', 'max' => 45],
            ['apellido','required'],
            [['fecnac'], 'default', 'value' => null],
            // se tiene que especificar format porque el datecontrol ya lo puso en formato mysql
            [['fecnac'],'date','format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dni' => Yii::t('app', 'Dni'),
            'apellido' => Yii::t('app', 'Apellido'),
            'nombre' => Yii::t('app', 'Nombre'),
            'nombre2' => Yii::t('app', 'Nombre2'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'fecdesde' => Yii::t('app', 'Fecha desde'),   
            'fechasta' => Yii::t('app', 'Fecha hasta'),  
            'nomCompleto' => Yii::t('app', 'Nombre completo'),           
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntradas()
    {
        return $this->hasMany(Entradas::className(), ['idpersonas_fk' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalidas()
    {
        return $this->hasMany(Salidas::className(), ['idpersonas_fk' => 'id']);
    }
}
