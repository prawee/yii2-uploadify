<?php
/*
 * 2014-10-15
 * @author Prawee Wongsa <konkeanweb@gmail.com>
 * original idea => xjflyttp/yii2-uploadify-widget
 */
namespace prawee\uploadify;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use prawee\uploadify\UploadifyAsset;
use yii\web\JsExpression;
use yii\helpers\Json;
use yii\web\View;

class Uploadify extends InputWidget
{
    /*
     * @var string
     * ['user/upload-photo']
     * ['upload']
     */
    public $url;
    /*
     * @var array
     * [
     *      'height'=>30,
     *      'width'=>120,
     *      'swf'=>'/uploadify/uploadify.swf',
     *      'uploader'=>'/uploadify/uploadify.php',
     * ]
     */
    public $jsOptions=[];
    /*
     * @var array
     */
    public $events=[
        'onCancel', 'onClearQueue', 'onDestroy', 'onDialogClose', 'onDialogOpen',
        'onDisable', 'onEnable', 'onFallback', 'onInit', 'onQueueComplete',
        'onSelect', 'onSelectError', 'onSWFReady', 'onUploadComplete',
        'onUploadError', 'onUploadProgress', 'onUploadStart', 'onUploadSuccess',
    ];
    
    /*
     * enable csrf verify
     * @var bool
     */
    public $csrf=true;
    
    /*
     * Tag
     * @var bool
     */
    public $renderTag=true;
    
    /*
     * Initializes the widget
     */
    public function init() {
        //init variable
        if(empty($this->url)){
            $this->url=  Url::to('index');
        }
        if(empty($this->id)){
            $this->id=$this->hasModel()? Html::getInputId($this->model, $this->attribute):$this->getId();
        }
        $this->options['id']=$this->id;
        if(empty($this->name)){
            $this->name=$this->hasModel() ? Html::getInputName($this->model, $this->attribute): $this->id;
        }
        
        //register css & js
        $asset = UploadifyAsset::register($this->view);
        
        //init options
        $this->initUploadifdyOptions($asset);
        
        parent::init();
    }
    
    /*
     * Renders the widget
     */
    public function run() {
        $this->registerScripts();
        if($this->renderTag===true){
            echo $this->renderTag();
        }
    }
    
    /*
     * init Uploadify options
     * @param [] $assets
     * @return void
     */
    private function initUploadifyOptions($asset){
        $baseUrl=$asset->baseUrl;
        
        $this->jsOptions['uploader']=$this->url;
        $this->jsOptions['swf']=$baseUrl.'/uploadify.swf';
        
        //csrf options
        if($this->csrf){
            $this->initUploadifyCsrfOption($this->jsOptions);
        }
        
        /*
         * JsExpression convert
         */
        foreach($this->jsOptions as $key => $val){
            if(in_array($key,$this->events) && !($val instanceof JsExpression)){
                $this->jsOptions[$key]=new JsExpression($val);
            }
        }
    }
    
    /*
     * uploadify csrf options
     * @param type $jsOptions
     * @return void
     */
    private function initUploadifyCsrfOption(&$jsOptions){
        $session = Yii::$app->session;
        $session->open();
        $sessionIdName = $session->getName();
        $sessionIdValue = $session->getId();

        $request = Yii::$app->request;
        $csrfName = $request->csrfParam;
        $csrfValue = $request->getCsrfToken();
        $session->set($csrfName, $csrfValue);

        $jsOptions['formData'] = [
            $sessionIdName => $sessionIdValue,
            $csrfName => $csrfValue,
        ];
    }
    
    /**
     * render file input tag
     * @return string
     */
    private function renderTag() {
        return Html::fileInput($this->name, null, $this->options);
    }
    
    /**
     * register script
     */
    private function registerScripts() {
        $jsonOptions = Json::encode($this->jsOptions);
        $script = <<<EOF
    $('#{$this->id}').uploadify({$jsonOptions});
EOF;
        $this->view->registerJs($script, View::POS_READY);
    }
}
