<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use common\models\User;

class RbacController extends Controller
{
    public function actionInit()
    {
		// desde country: ./yii rbac/init
		
		//exec('cd ..;pwd',$out,$status);
		
		/*
		exec('id -u -n',$out,$status);
		echo $out[0];
		echo $status;return 1;	
		*/
			
        if (!$this->confirm('Está seguro? Se reconstruirá todo el árbol de permisos.')) {
            return self::EXIT_CODE_NORMAL;
        }
		
        $auth = Yii::$app->authManager;
		
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
		$sinrol=$auth->createRole('sinRol');
		$sinrol->description='Sin rol asignado';

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
		$auth->addChild($consejo, $accederUser);
		$auth->addChild($administrador, $accederUser);	
		$auth->addChild($intendente, $accederUser);		
		$auth->addChild($opIntendencia, $accederUser);			

		$accederPorton=$auth->createPermission('accederPorton');
		$accederPorton->description='Acceso: elección de portón';
		$auth->add($accederPorton);
		$auth->addChild($intendente, $accederPorton);
		$auth->addChild($portero, $accederPorton);	
		$auth->addChild($opIntendencia, $accederPorton);						

		$accederIngreso=$auth->createPermission('accederIngreso');
		$accederIngreso->description='Acceso: ingreso';
		$auth->add($accederIngreso);
		$auth->addChild($intendente, $accederIngreso);
		$auth->addChild($portero, $accederIngreso);		
		$auth->addChild($opIntendencia, $accederIngreso);			
		
		$accederEgreso=$auth->createPermission('accederEgreso');
		$accederEgreso->description='Acceso: egreso';
		$auth->add($accederEgreso);	
		$auth->addChild($intendente, $accederEgreso);
		$auth->addChild($portero, $accederEgreso);	
		$auth->addChild($opIntendencia, $accederEgreso);						
	
		$accederEgresoGrupal=$auth->createPermission('accederEgresoGrupal');
		$accederEgresoGrupal->description='Acceso: egreso grupal';
		$auth->add($accederEgresoGrupal);	
		$auth->addChild($intendente, $accederEgresoGrupal);
		$auth->addChild($portero, $accederEgresoGrupal);
		$auth->addChild($opIntendencia, $accederEgresoGrupal);							

		$accederConsAccesos=$auth->createPermission('accederConsAccesos');
		$accederConsAccesos->description='Acceso: consulta accesos';
		$auth->add($accederConsAccesos);	
		$auth->addChild($consejo, $accederConsAccesos);
		$auth->addChild($administrador, $accederConsAccesos);		
		$auth->addChild($intendente, $accederConsAccesos);	
		$auth->addChild($opIntendencia, $accederConsAccesos);
		$auth->addChild($portero, $accederConsAccesos);	
		
		$accederStatsAccesos=$auth->createPermission('accederStatsAccesos');
		$accederStatsAccesos->description='Acceso: estadistica accesos';
		$auth->add($accederStatsAccesos);	
		$auth->addChild($consejo, $accederStatsAccesos);
		$auth->addChild($administrador, $accederStatsAccesos);		
		$auth->addChild($intendente, $accederStatsAccesos);	
		$auth->addChild($opIntendencia, $accederStatsAccesos);
		
		$exportarConsAccesos=$auth->createPermission('exportarConsAccesos');
		$exportarConsAccesos->description='Acceso: exportar accesos';
		$auth->add($exportarConsAccesos);	
		$auth->addChild($consejo, $exportarConsAccesos);
		$auth->addChild($administrador, $exportarConsAccesos);		
		$auth->addChild($intendente, $exportarConsAccesos);	
		$auth->addChild($opIntendencia, $exportarConsAccesos);
		
		$accederConsDentro=$auth->createPermission('accederConsDentro');
		$accederConsDentro->description='Acceso: consulta personas adentro';
		$auth->add($accederConsDentro);	
		$auth->addChild($consejo, $accederConsDentro);
		$auth->addChild($administrador, $accederConsDentro);		
		$auth->addChild($intendente, $accederConsDentro);
		$auth->addChild($opIntendencia, $accederConsDentro);	
		$auth->addChild($portero, $accederConsDentro);	
		
		$accederCortesStartStop=$auth->createPermission('accederCortesStartStop');
		$accederCortesStartStop->description='Acceso: Comenzar/finalizar corte energía';
		$auth->add($accederCortesStartStop);	
		$auth->addChild($intendente, $accederCortesStartStop);
		$auth->addChild($opIntendencia, $accederCortesStartStop);	
		$auth->addChild($portero, $accederCortesStartStop);		
		
		$accederConsCortes=$auth->createPermission('accederConsCortes');
		$accederConsCortes->description='Acceso: Consulta cortes energía';
		$auth->add($accederConsCortes);	
		$auth->addChild($consejo, $accederConsCortes);
		$auth->addChild($administrador, $accederConsCortes);		
		$auth->addChild($intendente, $accederConsCortes);
		$auth->addChild($opIntendencia, $accederConsCortes);	
		$auth->addChild($portero, $accederConsCortes);	
		
		$accederCarnets=$auth->createPermission('accederCarnets');
		$accederCarnets->description='Acceso: Generación de carnets';
		$auth->add($accederCarnets);	
		$auth->addChild($intendente, $accederCarnets);
		$auth->addChild($opIntendencia, $accederCarnets);	
				

		$exportarConsDentro=$auth->createPermission('exportarConsDentro');
		$exportarConsDentro->description='Acceso: exportar personas adentro';
		$auth->add($exportarConsDentro);	
		$auth->addChild($consejo, $exportarConsDentro);
		$auth->addChild($administrador, $exportarConsDentro);		
		$auth->addChild($intendente, $exportarConsDentro);
		$auth->addChild($opIntendencia, $exportarConsDentro);	
		
		$borrarAcceso=$auth->createPermission('borrarAcceso');
		$borrarAcceso->description='Eliminar: acceso';
		$auth->add($borrarAcceso);	
		$auth->addChild($intendente, $borrarAcceso);
		$auth->addChild($opIntendencia, $borrarAcceso);	
		
		$borrarCorte=$auth->createPermission('borrarCorte');
		$borrarCorte->description='Eliminar: corte de energía';
		$auth->add($borrarCorte);	
		$auth->addChild($intendente, $borrarCorte);
		$auth->addChild($opIntendencia, $borrarCorte);	
		
		$modificarCorte=$auth->createPermission('modificarCorte');
		$modificarCorte->description='Modificar: corte de energía';
		$auth->add($modificarCorte);	
		$auth->addChild($intendente, $modificarCorte);
		$auth->addChild($opIntendencia, $modificarCorte);
		$auth->addChild($portero, $modificarCorte);							
		
		$accederListaPersonas=$auth->createPermission('accederListaPersonas');
		$accederListaPersonas->description='Acceso: lista de personas';
		$auth->add($accederListaPersonas);	
		$auth->addChild($consejo, $accederListaPersonas);
		$auth->addChild($administrador, $accederListaPersonas);		
		$auth->addChild($intendente, $accederListaPersonas);
		$auth->addChild($opIntendencia, $accederListaPersonas);	
		$auth->addChild($portero, $accederListaPersonas);	
		
		$exportarListaPersonas=$auth->createPermission('exportarListaPersonas');
		$exportarListaPersonas->description='Acceso: exportar lista de personas';
		$auth->add($exportarListaPersonas);	
		$auth->addChild($consejo, $exportarListaPersonas);
		$auth->addChild($administrador, $exportarListaPersonas);		
		$auth->addChild($intendente, $exportarListaPersonas);
		$auth->addChild($opIntendencia, $exportarListaPersonas);	

		$borrarPersona=$auth->createPermission('borrarPersona');
		$borrarPersona->description='Eliminar: persona';
		$auth->add($borrarPersona);	
		$auth->addChild($intendente, $borrarPersona);
		$auth->addChild($opIntendencia, $borrarPersona);	
		
		$altaModificarPersona=$auth->createPermission('altaModificarPersona');
		$altaModificarPersona->description='Alta/modif.: persona';
		$auth->add($altaModificarPersona);	
		$auth->addChild($intendente, $altaModificarPersona);
		$auth->addChild($opIntendencia, $altaModificarPersona);	
		$auth->addChild($portero, $altaModificarPersona);	
		
		$cambiarPersona=$auth->createPermission('cambiarPersona');
		$cambiarPersona->description='Cambiar persona en todo el sistema';
		$auth->add($cambiarPersona);	
		$auth->addChild($intendente, $cambiarPersona);
		//$auth->addChild($opIntendencia, $cambiarPersona);		
		
		
		$accederListaAutorizantes=$auth->createPermission('accederListaAutorizantes');
		$accederListaAutorizantes->description='Acceso: lista de autorizantes';
		$auth->add($accederListaAutorizantes);	
		$auth->addChild($consejo, $accederListaAutorizantes);
		$auth->addChild($administrador, $accederListaAutorizantes);		
		$auth->addChild($intendente, $accederListaAutorizantes);
		$auth->addChild($opIntendencia, $accederListaAutorizantes);	
		$auth->addChild($portero, $accederListaAutorizantes);	

		$exportarListaAutorizantes=$auth->createPermission('exportarListaAutorizantes');
		$exportarListaAutorizantes->description='Acceso: exportar lista de autorizantes';
		$auth->add($exportarListaAutorizantes);	
		$auth->addChild($consejo, $exportarListaAutorizantes);
		$auth->addChild($administrador, $exportarListaAutorizantes);		
		$auth->addChild($intendente, $exportarListaAutorizantes);
		$auth->addChild($opIntendencia, $exportarListaAutorizantes);	

		$borrarAutorizante=$auth->createPermission('borrarAutorizante');
		$borrarAutorizante->description='Eliminar: autorizante';
		$auth->add($borrarAutorizante);	
		$auth->addChild($intendente, $borrarAutorizante);
		$auth->addChild($opIntendencia, $borrarAutorizante);	
		
		$altaAutorizante=$auth->createPermission('altaAutorizante');
		$altaAutorizante->description='Alta: autorizante';
		$auth->add($altaAutorizante);	
		$auth->addChild($intendente, $altaAutorizante);
		$auth->addChild($opIntendencia, $altaAutorizante);			
		
		
		$accederListaUf=$auth->createPermission('accederListaUf');
		$accederListaUf->description='Acceso: lista de U.F.';
		$auth->add($accederListaUf);	
		$auth->addChild($consejo, $accederListaUf);
		$auth->addChild($administrador, $accederListaUf);		
		$auth->addChild($intendente, $accederListaUf);
		$auth->addChild($opIntendencia, $accederListaUf);	
		$auth->addChild($portero, $accederListaUf);	

		$exportarListaUf=$auth->createPermission('exportarListaUf');
		$exportarListaUf->description='Acceso: exportar lista de U.F.';
		$auth->add($exportarListaUf);	
		$auth->addChild($consejo, $exportarListaUf);
		$auth->addChild($administrador, $exportarListaUf);		
		$auth->addChild($intendente, $exportarListaUf);
		$auth->addChild($opIntendencia, $exportarListaUf);	

		$borrarUf=$auth->createPermission('borrarUf');
		$borrarUf->description='Eliminar: U.F.';
		$auth->add($borrarUf);	
		$auth->addChild($intendente, $borrarUf);
		$auth->addChild($opIntendencia, $borrarUf);	
		
		$altaModificarUf=$auth->createPermission('altaModificarUf');
		$altaModificarUf->description='Alta/modif.: U.F.';
		$auth->add($altaModificarUf);	
		$auth->addChild($intendente, $altaModificarUf);
		$auth->addChild($opIntendencia, $altaModificarUf);	
		
		$accederListaUfTit=$auth->createPermission('accederListaUfTit');
		$accederListaUfTit->description='Acceso: lista titularidad UF';
		$auth->add($accederListaUfTit);	
		$auth->addChild($consejo, $accederListaUfTit);
		$auth->addChild($administrador, $accederListaUfTit);			
		$auth->addChild($intendente, $accederListaUfTit);
		$auth->addChild($opIntendencia, $accederListaUfTit);
		
		$altaModificarUfTit=$auth->createPermission('altaModificarUfTit');
		$altaModificarUfTit->description='Alta/modif.: titularidad de U.F.';
		$auth->add($altaModificarUfTit);	
		$auth->addChild($intendente, $altaModificarUfTit);
		$auth->addChild($opIntendencia, $altaModificarUfTit);						
				
		
		$altaPersonaIngEgr=$auth->createPermission('altaPersonaIngEgr');
		$altaPersonaIngEgr->description='Alta: persona (desde accesos)';
		$auth->add($altaPersonaIngEgr);	
		$auth->addChild($intendente, $altaPersonaIngEgr);
		$auth->addChild($opIntendencia, $altaPersonaIngEgr);	
		$auth->addChild($portero, $altaPersonaIngEgr);	
		
		$accederListaVehiculos=$auth->createPermission('accederListaVehiculos');
		$accederListaVehiculos->description='Acceso: lista de vehiculos';
		$auth->add($accederListaVehiculos);	
		$auth->addChild($consejo, $accederListaVehiculos);
		$auth->addChild($administrador, $accederListaVehiculos);		
		$auth->addChild($intendente, $accederListaVehiculos);
		$auth->addChild($opIntendencia, $accederListaVehiculos);	
		$auth->addChild($portero, $accederListaVehiculos);	
		
		$exportarListaVehiculos=$auth->createPermission('exportarListaVehiculos');
		$exportarListaVehiculos->description='Acceso: exportar lista de vehiculos';
		$auth->add($exportarListaVehiculos);	
		$auth->addChild($consejo, $exportarListaVehiculos);
		$auth->addChild($administrador, $exportarListaVehiculos);		
		$auth->addChild($intendente, $exportarListaVehiculos);
		$auth->addChild($opIntendencia, $exportarListaVehiculos);	

		$borrarVehiculo=$auth->createPermission('borrarVehiculo');
		$borrarVehiculo->description='Eliminar: vehiculo';
		$auth->add($borrarVehiculo);	
		$auth->addChild($intendente, $borrarVehiculo);
		$auth->addChild($opIntendencia, $borrarVehiculo);	
		
		$altaModificarVehiculo=$auth->createPermission('altaModificarVehiculo');
		$altaModificarVehiculo->description='Alta/modif.: persona';
		$auth->add($altaModificarVehiculo);	
		$auth->addChild($intendente, $altaModificarVehiculo);
		$auth->addChild($opIntendencia, $altaModificarVehiculo);
		$auth->addChild($portero, $altaModificarVehiculo);
			
		
		$altaVehiculoIngEgr=$auth->createPermission('altaVehiculoIngEgr');
		$altaVehiculoIngEgr->description='Alta: vehiculo (desde accesos)';
		$auth->add($altaVehiculoIngEgr);	
		$auth->addChild($intendente, $altaVehiculoIngEgr);
		$auth->addChild($opIntendencia, $altaVehiculoIngEgr);	
		$auth->addChild($portero, $altaVehiculoIngEgr);		
		
		$accederLibro=$auth->createPermission('accederLibro');
		$accederLibro->description='Acceso: libro de guardia';
		$auth->add($accederLibro);	
		$auth->addChild($intendente, $accederLibro);
		$auth->addChild($opIntendencia, $accederLibro);	
		$auth->addChild($portero, $accederLibro);	
		$auth->addChild($consejo, $accederLibro);
		$auth->addChild($administrador, $accederLibro);	
		
		$exportarLibro=$auth->createPermission('exportarLibro');
		$exportarLibro->description='Acceso: exportar libro de guardia';
		$auth->add($exportarLibro);	
		$auth->addChild($intendente, $exportarLibro);
		$auth->addChild($opIntendencia, $exportarLibro);	
		$auth->addChild($consejo, $exportarLibro);
		$auth->addChild($administrador, $exportarLibro);		
		
		$altaLibro=$auth->createPermission('altaLibro');
		$altaLibro->description='Alta: libro de guardia';
		$auth->add($altaLibro);	
		$auth->addChild($portero, $altaLibro);	
		
		$accederAgenda=$auth->createPermission('accederAgenda');
		$accederAgenda->description='Acceso: Agenda';
		$auth->add($accederAgenda);	
		$auth->addChild($consejo, $accederAgenda);
		$auth->addChild($administrador, $accederAgenda);		
		$auth->addChild($intendente, $accederAgenda);
		$auth->addChild($opIntendencia, $accederAgenda);	
		$auth->addChild($portero, $accederAgenda);	

		$borrarAgenda=$auth->createPermission('borrarAgenda');
		$borrarAgenda->description='Eliminar: Agenda';
		$auth->add($borrarAgenda);	
		$auth->addChild($intendente, $borrarAgenda);
		$auth->addChild($opIntendencia, $borrarAgenda);	
		$auth->addChild($portero, $borrarAgenda);			
		
		$altaModificarAgenda=$auth->createPermission('altaModificarAgenda');
		$altaModificarAgenda->description='Alta/modif.: Agenda';
		$auth->add($altaModificarAgenda);	
		$auth->addChild($intendente, $altaModificarAgenda);
		$auth->addChild($opIntendencia, $altaModificarAgenda);	
		$auth->addChild($portero, $altaModificarAgenda);	
		
		$accederListaInfrac=$auth->createPermission('accederListaInfrac');
		$accederListaInfrac->description='Acceso: lista de Infracciones/multas';
		$auth->add($accederListaInfrac);	
		$auth->addChild($consejo, $accederListaInfrac);
		$auth->addChild($administrador, $accederListaInfrac);		
		$auth->addChild($intendente, $accederListaInfrac);
		$auth->addChild($opIntendencia, $accederListaInfrac);	
		$auth->addChild($portero, $accederListaInfrac);	

		$exportarListaInfrac=$auth->createPermission('exportarListaInfrac');
		$exportarListaInfrac->description='Acceso: exportar lista de infracciones/multas';
		$auth->add($exportarListaInfrac);	
		$auth->addChild($consejo, $exportarListaInfrac);
		$auth->addChild($administrador, $exportarListaInfrac);		
		$auth->addChild($intendente, $exportarListaInfrac);
		$auth->addChild($opIntendencia, $exportarListaInfrac);	

		$borrarInfrac=$auth->createPermission('borrarInfrac');
		$borrarInfrac->description='Eliminar: Infracción/multa';
		$auth->add($borrarInfrac);	
		$auth->addChild($intendente, $borrarInfrac);
		$auth->addChild($opIntendencia, $borrarInfrac);	
		
		$altaModificarInfrac=$auth->createPermission('altaModificarInfrac');
		$altaModificarInfrac->description='Alta/modif.: Infracción';
		$auth->add($altaModificarInfrac);	
		$auth->addChild($intendente, $altaModificarInfrac);
		$auth->addChild($opIntendencia, $altaModificarInfrac);
		$auth->addChild($portero, $altaModificarInfrac);	
		
		$altaModificarMulta=$auth->createPermission('altaModificarMulta');
		$altaModificarMulta->description='Alta/modif.: Multa';
		$auth->add($altaModificarMulta);	
		$auth->addChild($intendente, $altaModificarMulta);
		$auth->addChild($opIntendencia, $altaModificarMulta);
		
		$accederRendicMultas=$auth->createPermission('accederRendicMultas');
		$accederRendicMultas->description='Acceso: rendición de multas';
		$auth->add($accederRendicMultas);	
		$auth->addChild($consejo, $accederRendicMultas);
		$auth->addChild($administrador, $accederRendicMultas);		
		$auth->addChild($intendente, $accederRendicMultas);
		$auth->addChild($opIntendencia, $accederRendicMultas);	
		
		$accederParametros=$auth->createPermission('accederParametros');
		$accederParametros->description='Acceso: parámetros varios';
		$auth->add($accederParametros);	
		$auth->addChild($consejo, $accederParametros);
		$auth->addChild($administrador, $accederParametros);		
		$auth->addChild($intendente, $accederParametros);
		$auth->addChild($opIntendencia, $accederParametros);
		
		$modificarParametros=$auth->createPermission('modificarParametros');
		$modificarParametros->description='Modificar: parámetros varios';
		$auth->add($modificarParametros);	
		$auth->addChild($intendente, $modificarParametros);
		$auth->addChild($opIntendencia, $modificarParametros);	
		
		$accederAutManualAccesos=$auth->createPermission('accederAutManualAccesos');
		$accederAutManualAccesos->description='Acceso: Autorización acceso manual';
		$auth->add($accederAutManualAccesos);	
		$auth->addChild($consejo, $accederAutManualAccesos);
		$auth->addChild($administrador, $accederAutManualAccesos);		
		$auth->addChild($intendente, $accederAutManualAccesos);
		$auth->addChild($opIntendencia, $accederAutManualAccesos);
		
		$altaModificarAutManualAccesos=$auth->createPermission('altaModificarAutManualAccesos');
		$altaModificarAutManualAccesos->description='Alta/modif.: Autorización acceso manual';
		$auth->add($altaModificarAutManualAccesos);	
		$auth->addChild($intendente, $altaModificarAutManualAccesos);
		$auth->addChild($opIntendencia, $altaModificarAutManualAccesos);										
											
									

			
		// Asignaciones de roles a usuarios OJO con el id de user
		
		/*
		
		$auth->assign($administrador, 8);
        $auth->assign($consejo, 7);
        $auth->assign($intendente, 9);
        
        $auth->assign($portero,11);
        $auth->assign($sinrol,15);
        *
        
        */
 
 		//$auth->assign($administrador, 2);
        //$auth->assign($consejo, 1);

		$users=User::find()->all();
		foreach ($users as $u) {
			if ($u->id == 1) {$auth->assign($consejo, $u->id);continue;}
			if ($u->id == 2) {$auth->assign($administrador, $u->id);continue;}
			$auth->assign($sinrol,$u->id);
		}
        
     
		
    }
}
