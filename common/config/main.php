<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'language'=>'es',     
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',   
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        
        
        'i18n' => [
			'translations' => [
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@common/messages',
				],
			],
		],
        
        
    ],
];
