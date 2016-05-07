<?php

namespace app\assets;

use yii\web\AssetBundle;

class BarreraAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'css/alertify.core.css',
		'css/alertify.default.css',		
    ];
    public $js = [
        'js/alertify.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',

    ];
}
