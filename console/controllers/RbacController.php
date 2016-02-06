<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
		
		// Creacion de roles
		
		$consejo=$auth->createRole('consejo');
		$consejo->description='Rol: Consejo';
		$administrador=$auth->createRole('administrador');
		$administrador->description='Rol: Administrador';
		$intendente=$auth->createRole('intendente');
		$intendente->description='Rol: Intendente';
		$opIntendencia=$auth->createRole('opIntendencia');
		$opIntendencia->description='Rol: Operador de Intendencia';
		$arquitecto=$auth->createRole('arquitecto');
		$arquitecto->description='Rol: Arquitecto';	
		$portero=$auth->createRole('portero');
		$portero->description='Rol: Portero';		
		$propietario=$auth->createRole('propietario');
		$propietario->description='Rol: Propietario';		
		$guardia=$auth->createRole('guardia');
		$guardia->description='Rol: Guardia';	
		$sinrol=$auth->createRole('sinRol');
		$sinrol->description='Rol: Sin rol asignado';

		$auth->add($consejo);
		$auth->add($administrador);		
		$auth->add($intendente);
		$auth->add($opIntendencia);
		$auth->add($arquitecto);
		$auth->add($portero);		
		$auth->add($propietario);
		$auth->add($guardia);	
		$auth->add($sinrol);			

		// Creacion de permisos
		$accederUser=$auth->createPermission('accederUser');
		$accederUser->description='Acceso: usuarios';
		$auth->add($accederUser);
		
		$accederUserRol=$auth->createPermission('accederUserRol');
		$accederUserRol->description='Acceso: rol de usuarios';
		$auth->add($accederUserRol);
		
		$accederPorton=$auth->createPermission('accederPorton');
		$accederPorton->description='Acceso: elección de portón';
		$auth->add($accederPorton);

		$accederEntradas=$auth->createPermission('accederEntradas');
		$accederEntradas->description='Acceso: Entradas';
		$auth->add($accederEntradas);

		
		// Asignaciones de permisos a roles
		
		$auth->addChild($consejo, $accederUser);
		$auth->addChild($administrador, $accederUser);	
		$auth->addChild($intendente, $accederUser);	
		
		$auth->addChild($consejo, $accederUserRol);
		$auth->addChild($administrador, $accederUserRol);	
		$auth->addChild($intendente, $accederUserRol);	
		
		$auth->addChild($intendente, $accederPorton);		
		$auth->addChild($intendente, $accederEntradas);
				
		// Asignaciones de roles a usuarios OJO con el id de user
		
		$auth->assign($administrador, 8);
        $auth->assign($consejo, 7);
        $auth->assign($intendente, 9);
		
    }
}
