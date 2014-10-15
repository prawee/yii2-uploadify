<?php
/*
 * 2014-10-15
 * @author Prawee Wongsa <konkeanweb@gmail.com>
 */
namespace prawee\uploadify;

use yii\web\AssetBundle;

class UploadifyAsset extends AssetBundle{
    /*
     * @inheritdoc
     */
    public $sourcePath='@prawee/uploadify/assets';
    
    public $css=[
        
    ];
    
    public $js=[
        
    ];
    
    public $depends=[
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
