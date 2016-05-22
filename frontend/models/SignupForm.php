<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este nombre de usuario ya existe.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Esta dirección de correo ya fue utilizada.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
   public function attributeLabels()
    {
        return [
            'username' => 'Usuario',
            'password' => 'Clave',
            'email' => 'Correo',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->acceso_externo=false;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
				// Aca es donde se asigna el rol "sinRol" a los nuevos usuarios
				$auth = Yii::$app->authManager;
				$sinRol = $auth->getRole('sinRol');
				$auth->assign($sinRol, $user->getId());
                return $user;
            }
        }

        return null;
    }
}
