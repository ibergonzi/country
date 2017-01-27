<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;


class NuevopermisoController extends Controller
{
    public function actionInit()
    {
		// desde country: ./yii nuevopermiso/init
		
		
        if (!$this->confirm('Está seguro? Se crean permisos nuevos')) {
            return self::EXIT_CODE_NORMAL;
        }
		
        $auth = Yii::$app->authManager;
		
		// Seteo de roles ya existentes
		
		$consejo=$auth->getRole('consejo');
		$administrador=$auth->getRole('administrador');
		$intendente=$auth->getRole('intendente');
		$opIntendencia=$auth->getRole('opIntendencia');
		$arquitecto=$auth->getRole('arquitecto');
		$portero=$auth->getRole('portero');
		$propietario=$auth->getRole('propietario');
		$guardia=$auth->getRole('guardia');



		// Creacion de permisos
				
		
		$accederAutorizados=$auth->createPermission('accederAutorizados');
		$accederAutorizados->description='Acceso: autorizaciones perm./event.';
		$auth->add($accederAutorizados);	
		$auth->addChild($consejo, $accederAutorizados);
		$auth->addChild($administrador, $accederAutorizados);		
		$auth->addChild($intendente, $accederAutorizados);
		$auth->addChild($opIntendencia, $accederAutorizados);	
		$auth->addChild($portero, $accederAutorizados);	
		
		$exportarAutorizados=$auth->createPermission('exportarAutorizados');
		$exportarAutorizados->description='Exportar: autorizaciones perm./event.';
		$auth->add($exportarAutorizados);	
		$auth->addChild($consejo, $exportarAutorizados);
		$auth->addChild($administrador, $exportarAutorizados);		
		$auth->addChild($intendente, $exportarAutorizados);
		$auth->addChild($opIntendencia, $exportarAutorizados);	

		$borrarAutorizados=$auth->createPermission('borrarAutorizados');
		$borrarAutorizados->description='Eliminar: autorizaciones perm./event.';
		$auth->add($borrarAutorizados);	
		$auth->addChild($intendente, $borrarAutorizados);
		$auth->addChild($opIntendencia, $borrarAutorizados);	
		
		$altaAutorizados=$auth->createPermission('altaAutorizados');
		$altaAutorizados->description='Alta/modif.: autorizaciones perm./event.';
		$auth->add($altaAutorizados);	
		$auth->addChild($intendente, $altaAutorizados);
		$auth->addChild($opIntendencia, $altaAutorizados);	

		
		
		$accederRoles=$auth->createPermission('accederRoles');
		$accederRoles->description='Acceso: Roles en el sistema';
		$auth->add($accederRoles);	
		$auth->addChild($intendente, $accederRoles);
		$auth->addChild($opIntendencia, $accederRoles);		
								
		$accederPermisos=$auth->createPermission('accederPermisos');
		$accederPermisos->description='Acceso: Permisos asignados a roles';
		$auth->add($accederPermisos);	
		$auth->addChild($intendente, $accederPermisos);
		$auth->addChild($opIntendencia, $accederPermisos);											
       
     
		
    }
}
