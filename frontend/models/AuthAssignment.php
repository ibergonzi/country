<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('app', 'Rol'),
            'user_id' => Yii::t('app', 'ID usuario'),
            'created_at' => Yii::t('app', 'Fec.Creac.'),
        ];
    }
    
    public static function listaRoles()
    {
		// devuelve array de roles filtrado por el rol del usuario activo

		$rol=User::getRol(Yii::$app->user->getId());
		// se traen todos los roles
		$auth = Yii::$app->authManager;
		$roles=$auth->getRoles();
		// aca se filtran
		switch($rol->name) {
			case (string)"intendente": 
				unset($roles['administrador'],$roles['consejo']);		
				break;
			case (string)"administrador": 
				unset($roles['consejo']);		
				break;
			case (string)"consejo": 
				break;				
			default:	
				unset($roles['intendente'],$roles['administrador'],$roles['consejo']);			
		}		
		return $roles;

	}    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItem()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }
    
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id']);
    }    
}
