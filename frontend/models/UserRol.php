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
 * @property string $name
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'email'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            [['item_name', 'name'], 'string', 'max' => 64]
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
            'name' => Yii::t('app', 'Name'),
        ];
    }
}
