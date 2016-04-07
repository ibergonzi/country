<?php

return [
    'adminEmail' => 'miraflores.adm.notif@gmail.com',
    
	//basicamente para las exportaciones	
    'lblName'=> 'Barrio Miraflores',
    'lblName2'=>'Funes Hills',
    
    'sinVehiculo.id'=>2,
    'sinVehiculo.patente'=>'9999',
    
    'bicicleta.id'=>1,
    'bicicleta.patente'=>'8888',
    
    // el vehiculo generico se utiliza como default en la definicion de la llave de la barrera
    'generico.id'=>3,
    'generico.patente'=>'7777',    
    
    // ojo con este valor, si es muy grande hay que tocar en los php.ini los max_execution_time
    'max-rows-gridview'=>400,
    
    // defaults de paginación de grillas
    'libro.defaultPageSize'=>5,
    'libro.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50],
    
    'accesosAut.defaultPageSize'=>10,
    'accesosAut.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100, 200=>200, 300=>300],  
    
    'accesos.defaultPageSize'=>10,
    'accesos.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100, 200=>200, 300=>300],  
    
    'accesosEgr.defaultPageSize'=>5,
    'accesosEgr.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50],   
    
    'personas.defaultPageSize'=>15,
    'personas.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100],

    'vehiculos.defaultPageSize'=>15,
    'vehiculos.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100], 
    
    'user.defaultPageSize'=>15,
    'user.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50, 100=>100],   
    
    'cortesEnergia.defaultPageSize'=>5,
    'cortesEnergia.sizes'=>[2=>2, 5=>5, 10=>10, 15=>15, 20=>20, 25=>25, 50=>50],                    
 


];
