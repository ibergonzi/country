<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

use common\models\User;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item';
    }

	public static function getListaRoles()
	{
		$query = self::find()->where(['type'=>1]);
		// Aca se cocina lo que deberia ver el usuario segun su rol
		$rol=User::getRol(Yii::$app->user->getId());
		// el administrador no puede ver al usuario consejo, el consejo puede ver a todos, 
		// el intendente no puede ver al consejo ni administrador
		switch($rol->name) {
			case (string)"administrador": 
				$query->andFilterWhere(['not in','name',['consejo']]);
				break;
			case (string)"consejo": 
				break;
			case (string)"intendente": 
				$query->andFilterWhere(['not in','name',['administrador','consejo']]);
				break;				
			default:
				$query->andFilterWhere(['not in','name',['intendente','administrador','consejo']]);
		}	
		// el rol "sinRol" es especial, no se puede usar		
		$query->andFilterWhere(['not in','name',['sinRol']]);		
		
		
		$opciones=$query->asArray()->all();
		return ArrayHelper::map($opciones, 'name', 'description');
	} 
	
	public static function getListaPermisos()
	{
		$opciones = self::find()->where(['type'=>2])->asArray()->all();
		return ArrayHelper::map($opciones, 'name', 'description');
	} 	


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'type'], 'unique', 
				'targetAttribute' => ['name', 'type'], 
				'message' => 'Ya existe un rol con ese nombre'],            
            [['name', 'rule_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Descrip.Rol'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemRoles()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemPermisos()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }
    
    public function getUsers() 
    {
		return $this->hasMany(AuthAssignment::className(),['item_name'=>'name']);
	}
}
