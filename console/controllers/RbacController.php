<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

		/*
		
		// Creacion de roles
		$consejo=$auth->createRole('consejo');
		$consejo->description='Consejo';
		$administrador=$auth->createRole('administrador');
		$administrador->description='Administrador';
		$intendente=$auth->createRole('intendente');
		$intendente->description='Intendente';
		$opIntendencia=$auth->createRole('opIntendencia');
		$opIntendencia->description='Operador de Intendencia';
		$arquitecto=$auth->createRole('arquitecto');
		$arquitecto->description='Arquitecto';	
		$portero=$auth->createRole('portero');
		$portero->description='Portero';		
		$propietario=$auth->createRole('propietario');
		$propietario->description='Propietario';		
		$guardia=$auth->createRole('guardia');
		$guardia->description='Guardia';	
		*/	
		$sinrol=$auth->createRole('sinRol');
		$sinrol->description='Sin rol asignado';
		$auth->add($sinrol);
	
		/*
		$auth->add($consejo);
		$auth->add($administrador);		
		$auth->add($intendente);
		$auth->add($opIntendencia);
		$auth->add($arquitecto);
		$auth->add($portero);		
		$auth->add($propietario);
		$auth->add($guardia);		

		// Creacion de permisos
		$accederUser=$auth->createPermission('accederUser');
		$accederUser->description='Acceder: usuarios';
		$auth->add($accederUser);
		
		$accederUserRol=$auth->createPermission('accederUserRol');
		$accederUserRol->description='Acceder: rol de usuarios';
		$auth->add($accederUserRol);
		

		
		// Asignaciones de permisos a roles
		$auth->addChild($consejo, $accederUser);
		$auth->addChild($administrador, $accederUser);	
		$auth->addChild($intendente, $accederUser);	
		
		$auth->addChild($consejo, $accederUserRol);
		$auth->addChild($administrador, $accederUserRol);	
		$auth->addChild($intendente, $accederUserRol);	
		
		
		// Asignaciones de roles a usuarios OJO con el id de user
		$auth->assign($administrador, 8);
        $auth->assign($consejo, 7);
        $auth->assign($intendente, 9);
        
        */
        
        
        /* 
		$roles=$auth->getRoles();
		foreach ($roles as $r) {
			echo $r->name;
		}
		*/
		
		
    }
}
