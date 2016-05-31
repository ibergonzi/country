<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

use \kartik\datecontrol\Module;
date_default_timezone_set('America/Argentina');
return [
    'id' => 'app-frontend',
    'name'=>'Miraflores',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
	'timeZone' => 'UTC-3',    
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        
        'urlManager' => [
          'showScriptName' => false, 
          'enablePrettyUrl' => true
        ],
        'formatter' => [
			'class' => 'yii\i18n\Formatter',
			
			'dateFormat' => 'php:d/m/Y',
			'datetimeFormat' => 'php:d/m/Y H:i:s',
			'timeFormat' => 'php:H:i:s',
			//'timeZone' => 'America/Argentina/Buenos_Aires',
			'timeZone' => 'UTC',
			
			'decimalSeparator'=>',',
			'thousandSeparator'=>'.',
		],

        'assetManager' => [
            'appendTimestamp' => true,
        ],

    ],
    'modules' => [
		'gridview' =>  [
			'class' => '\kartik\grid\Module',
			
			'i18n' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@common/messages',
					'forceTranslation' => true
				]
						
			],

		
	   'datecontrol' =>  [
			'class' => 'kartik\datecontrol\Module',
	  
			'displaySettings' => [
				Module::FORMAT_DATE => 'php:d/m/Y',
				Module::FORMAT_TIME => 'php:H:i:s',
				Module::FORMAT_DATETIME => 'php:d/m/Y H:i:s', 
			],
			
			'saveSettings' => [
				Module::FORMAT_DATE => 'php:Y-m-d', 
				Module::FORMAT_TIME => 'php:H:i:s',
				Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
			],
	
			// set your display timezone
			'displayTimezone' => 'America/Argentina/Buenos_Aires',
	 
			// set your timezone for date saved to db
			'saveTimezone' => 'America/Argentina/Buenos_Aires',
			
			'ajaxConversion'=>true,
			
			// automatically use kartik\widgets for each of the above formats
			'autoWidget' => true,
	 
			
			// default settings for each widget from kartik\widgets used when autoWidget is true
			'autoWidgetSettings' => [
				Module::FORMAT_DATE => ['type'=>2,
						'pickerButton'=>false, 
						'pluginOptions'=>['autoclose'=>true,],
						'removeButton'=>['class'=>'btn-sm btn-default'],
				], 
				
				Module::FORMAT_DATETIME => [], // setup if needed
				Module::FORMAT_TIME => [], // setup if needed
			],
			
			// custom widget settings that will be used to render the date input instead of kartik\widgets,
			// this will be used when autoWidget is set to false at module or widget level.
			'widgetSettings' => [
				Module::FORMAT_DATE => [
					'class' => 'kartik\daterange\DateRangePicker', // example
					/*
					'options' => [
						'dateFormat' => 'php:d-M-Y',
						'options' => ['class'=>'form-control'],
					]
					*/
				]
			]
			// other settings
			
		]			
			
	],
    'params' => $params,
];
