<?php

namespace frontend\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "libro".
 *
 * @property integer $id
 * @property string $texto
 * @property integer $idporton
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class Libro extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'libro';
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
            [['texto', 'idporton', ], 'required'],
            [['id', 'idporton', 'created_by', 'updated_by'], 'integer'],
            [['created_by','created_at', 'updated_by','updated_at'], 'safe'],
            [['texto'], 'string', 'max' => 500]
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'texto' => Yii::t('app', 'Texto'),
            'idporton' => Yii::t('app', 'Portón'),
            'created_by' => Yii::t('app', 'Creado por'),
            'created_at' => Yii::t('app', 'Creado en'),
            'updated_by' => Yii::t('app', 'Modificado por'),
            'updated_at' => Yii::t('app', 'Modificado en'),
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
