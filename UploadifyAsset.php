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
        'css/uploadify.css',
    ];
    
    public $js=[];
    
    public $depends=[
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    
    public $publishOptions = ['forceCopy' => YII_DEBUG];
    
    private function getJs() {
        return [
            YII_DEBUG ? 'jquery.uploadify.js':'jquery.uploadify.min.js',
        ];
    }
    public function init() {
        if(empty($this->js)){
            $this->js=$this->getJs();
        }
        return parent::init();
    }
}
