<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_rol".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $item_name
 * @property string $description
 */
class UserRol extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_rol';
    }
    
	// creado a mano para que gii pueda crear controller y views
	public static function primaryKey()
    {     
        return ['id'];   
    }    
    
    public static function listaRoles()
    {
		// devuelve array de roles filtrado por el rol del usuario activo
	    $auth = Yii::$app->authManager;
	    // se averigua el rol del usuario activo
		$rolesUser=$auth->getRolesByUser(Yii::$app->user->getId());
		foreach ($rolesUser as $rol) {
			// acá no hace nada, se hace asi porque siempre hay un solo rol por usuario
		}		
		
		// se traen todos los roles
		$roles=$auth->getRoles();
		// aca se filtran
		switch($rol->name) {
			case (string)"intendente": 
				unset($roles['intendente'],$roles['administrador'],$roles['consejo']);
				break;
			case (string)"administrador": 
				unset($roles['administrador'],$roles['consejo']);		
				break;
			case (string)"consejo": 
				unset($roles['consejo']);		
				break;
		}		
		return $roles;

	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'email'], 'safe'],
            [['username', 'email'], 'string', 'max' => 255],
            [['item_name', 'description'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'item_name' => Yii::t('app', 'Item Name'),
            'description' => Yii::t('app', 'description'),
        ];
    }
}
