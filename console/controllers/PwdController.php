<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;

class PwdController extends Controller
{
    public function actionInit()
    {

     $uu=User::find()->all();
     //$uu=User::findOne(15);     
     foreach ($uu as $u) {
		 echo $u->id;
		 echo '<br/>';
		 $u->setPassword($u->password_hash);
		 $u->generateAuthKey();
		 $u->save();
	 }
		
    }
}
