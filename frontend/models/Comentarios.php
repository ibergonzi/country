<?php

namespace frontend\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "comentarios".
 *
 * @property integer $id
 * @property string $comentario
 * @property string $model
 * @property integer $model_id
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 */
class Comentarios extends \yii\db\ActiveRecord
{
	
	public static function getComentariosByModelId($modelName,$modelID)
    {
	    $ms=self::find()->where(['model'=>$modelName,'model_id'=>$modelID])->orderBy(['created_at'=>SORT_DESC])->all();
		return $ms;
	}	
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comentarios';
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
            [['comentario', ], 'required'],
            [['model_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','model', 'model_id', 'created_by','updated_by'], 'safe'],
            [['comentario'], 'string', 'max' => 500],
            [['model'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'comentario' => Yii::t('app', 'Comentario'),
            'model' => Yii::t('app', 'Model'),
            'model_id' => Yii::t('app', 'Model ID'),
            'created_by' => Yii::t('app', 'Creado por'),
            'created_at' => Yii::t('app', 'Creado el'),
            'updated_by' => Yii::t('app', 'Modificado por'),
            'updated_at' => Yii::t('app', 'Modificado el'),
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
