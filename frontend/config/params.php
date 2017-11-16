<?php

return [
    'adminEmail' => 'miraflores.adm.notif@gmail.com',
    
    // ip de la red local (se usa el * para indicar todos los numeros, por ejemplo: 192.168.*)
    'localIP'=>'192.168.0.*',
    
	// parametros de exportaciones	
    'lblName'=> 'Barrio Miraflores',
    'lblName2'=>'Funes Hills',
    
    'sinVehiculo.id'=>2,
    'sinVehiculo.patente'=>'9999',
    
    'bicicleta.id'=>1,
    'bicicleta.patente'=>'8888',
    
    // el vehiculo generico se utiliza como default en la definicion de la llave de la barrera
    'generico.id'=>3,
    'generico.patente'=>'7777',    
    
    // la uf generica 
    'uf.generica'=>0,
    
    // concepto COPROPIETARIO (se utiliza para setear el concepto cuando se trata de un autorizante)
    'concepto.COPROPIETARIO'=>1,
    
    // Cantidad de dias que se adicionan a la fecha del dia para controlar los seguros que se estén por vencer
    // esto se utiliza en el ingreso (function refreshListas)
    'fecSeguroDias'=>2,
    
    // Bandera para exigir la obligatoriedad de que el vehiculo tenga seguro vigente
    // actualmente, pedido por Intendencia, no se exige por lo tanto se permite grabar el movimiento
    // controlaSegVehiculos == 'S' --> controla vencimientos y no continua grabando 
    // controlaSegVehiculos == 'N' --> no hace ningun control y permite grabar igual
    'controlaSegVehiculos'=>'N',
    
    // Cantidad de dias que se utiliza para calcular la "fecha desde" del filtro de la consulta de accesos (accesos/index)
    'filtroConsAccesosDias'=>6,
    
    // defaults de paginación de grillas, OJO verificar que no haya que tocar en los php.ini los max_execution_time
    'libro.defaultPageSize'=>5,
    'libro.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50],
    
    'accesosAut.defaultPageSize'=>10,
    'accesosAut.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100, 200=>200, 300=>300],  
    
    'accesos.defaultPageSize'=>10,
    'accesos.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100, 200=>200, 300=>300],  
    
    'accesosEgr.defaultPageSize'=>25,
    'accesosEgr.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50],   
    
    'personas.defaultPageSize'=>15,
    'personas.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100],
    
    'autorizantes.defaultPageSize'=>15,
    'autorizantes.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100],    

    'vehiculos.defaultPageSize'=>15,
    'vehiculos.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100], 
    
    'user.defaultPageSize'=>15,
    'user.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100],   
    
    'cortesEnergia.defaultPageSize'=>5,
    'cortesEnergia.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50],                    
 
    'uftitularidad.defaultPageSize'=>15,
    'uftitularidad.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100],
    
    'uf.defaultPageSize'=>20,
    'uf.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100,200=>200,400=>400],
    
    'infracConceptos.defaultPageSize'=>20,
    'infracConceptos.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50], 
    
    'infracciones.defaultPageSize'=>15,
    'infracciones.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100,200=>200,400=>400],        

    'infracUnidades.defaultPageSize'=>5,
    'infracUnidades.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50], 
   
    'tiposdoc.defaultPageSize'=>20,
    'tiposdoc.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50], 
    
    'movimUf.defaultPageSize'=>15,
    'movimUf.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50],
    
    'generadores.defaultPageSize'=>5,
    'generadores.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50], 
    
    'autorizados.defaultPageSize'=>20,
    'autorizados.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100],   
    
    'autorizadosHorarios.defaultPageSize'=>7,
    'autorizadosHorarios.sizes'=>[7=>7, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50],                 

];
