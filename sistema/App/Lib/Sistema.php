<?php

/**
 * Created by Grupo B+M
 * User: Esdras Castro
 * Date: 17/08/2016
 */
namespace Lib;
use BadFunctionCallException;
use Controle\Login;

class Sistema
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];
    protected static $urlReferrer = '';
    protected $debugError = false;
    protected static $htmlPath = '';
    protected static $controlePath = '';
    protected static $modeloPath = '';
    protected static $publicoPath = '';
    protected static $absPath = '';
    protected static $title = "";
    protected static $javascript = [];
    protected static $css = [];
    protected static $jsScript = [];
    protected static $styleScript = [];
    protected static $authenticated = false;
    protected static $basePath = '';
    protected static $menudesktop = [];
    protected static $menumobile = [];
    protected static $privilegio = '';
    /*protected static $authorizedPage = array(
        'administrator'=>[],
        'manager'=>[],
        'tester'=>[]
    );*/
    protected static $sessionname;

    public function __construct($dir, $basepath='')
    {
        self::$absPath = $dir;
        self::$basePath = $basepath;
        self::$controlePath = self::$absPath.'/App/Controle/';
        self::$modeloPath = self::$absPath.'/App/Modelo/';
        self::$publicoPath = self::$absPath.'/App/Publico/';
        self::$htmlPath = self::$absPath.'/App/Publico/html/';
        \Lib\Connection::connect(DSN,USER,PASS);

        $this->prepareUrl();

        try {
            $session = Session::get(Login::sessionName());
            $username = $session['username'];

            if($this->controller!='Login'){
                if(!\Controle\Login::logar($username,'',true)) {
                    self::redirect(self::$basePath . 'sistema/login/');
                }
            }else{
                if(\Controle\Login::logar($username,'',true)) {
                    self::redirect(self::$absPath.'sistema/');
                }
            }

            if (class_exists('Controle\\' . $this->controller)) {
                call_user_func_array(['Controle\\' . $this->controller, $this->method], $this->params);
            }
            else {
                call_user_func_array(['Controle\\Error', 'error404'], []);
            }
        }catch(BadFunctionCallException $err){
            if($this->debugError){
                echo "<pre>";
                print_r($err->getTrace());
                echo "</pre>";
            }
        }
    }

    /*
    public function addPagePrivilege($usertype, $pagename)
    {
        $usertype = self::removeSpecialChar($usertype);
        $pagename = self::removeSpecialChar($pagename);
        if(array_key_exists($usertype, self::$authorizedPage)){
            if(!in_array($pagename, self::$authorizedPage[$usertype])){
                array_push(self::$authorizedPage[$usertype], $pagename);
            }
        }else{
            die('O tipo de usuário informado não existe.');
        }
    }

    public function hasPrivilege($usertype, $pagename){
        $usertype = self::removeSpecialChar($usertype);
        $pagename = self::removeSpecialChar($pagename);
        if(array_key_exists($usertype, self::$authorizedPage)){
            if(!in_array($pagename, self::$authorizedPage[$usertype])){
                return true;
            }
        }

        return false;
    }
    */

    /*** Menu Desktop ***/
    public function setMenuDesktop($url, $iconname, $name, $title='', $active=false, $urllocal=true)
    {
        $icon = !empty($iconname)?"<i class='material-icons left'>{$iconname}</i>":'';
        $url = $urllocal?self::$basePath.$url:$url;
        $active = $active?"class='active'":"";

        array_push(self::$menudesktop, "<li {$active}><a href='{$url}' title='{$title}'>{$icon} {$name}</a>");

        return $this;
    }
    public function getMenuDesktop()
    {
        $html = '';
        foreach (self::$menudesktop as $val){
            $html .= "<ul class='right hide-on-med-and-down'>{$val}\n</ul>";
        }

        return $html;
    }
    /*** Fim Menu Desktop ***/
    public function setMenuMobile($url, $iconname, $name, $title='', $active=false, $urllocal=true)
    {
        $icon = !empty($iconname)?"<i class='material-icons left'>{$iconname}</i>":'';
        $url = $urllocal?self::$basePath.$url:$url;
        $active = $active?"class='active'":"";

        array_push(self::$menudesktop, "<li {$active}><a href='{$url}' title='{$title}'>{$icon} {$name}</a>");

        return $this;
    }
    public function getMenuMobile()
    {
        $html = '';
        foreach (self::$menudesktop as $val){
            $html .= "<ul class='side-nav' id='navbar_default'>{$val}\n</>";
        }

        return $html;
    }
    /*** Menu Mobile ***/
    /*** Fim Menu Mobile ***/

    /*** Javascript ***/
    public function setJs($src)
    {
        if(!empty($src)) array_push(self::$javascript, '<script src="'.$src.'" type="text/javascript"></script>');
        return $this;
    }
    public function setJsScript($string)
    {
        if(!empty($string)) array_push(self::$jsScript, $string);
        return $this;
    }
    public function getJsScript($variablename='javascript'){
        $html = '';
        foreach (self::$$variablename as $val){
            $html .= "{$val}\n";
        }

        return $html;
    }
    /*** Fim Javascript ***/
    /*** Style Script ***/
    public function setCss($href)
    {
        if(!empty($string)) array_push(self::$styleScript, '<link href="'.$string.'" rel="stylesheet" type="text/css" />');
        return $this;
    }
    public function setStyleScript($string)
    {
        if(!empty($string)) array_push(self::$styleScript, $string);
        return $this;
    }
    public function getStyleScript($variablename='styleScript'){
        $html = '';
        foreach (self::$$variablename as $val){
            $html .= "{$val}\n";
        }

        return $html;
    }
    /*** Fim Style Script ***/


    protected function redirect($href)
    {
        $href = filter_var(strtolower(trim(trim($href),'/')), FILTER_SANITIZE_URL);
        if(!empty($href)) header('Location: '.$href);
    }

    protected function prepareUrl($url='')
    {
        $this->urlReferrer = $_SERVER['URL_REFERRER'];

        if(empty($url)) $url = isset($_REQUEST['url'])?filter_var(strtolower(trim(trim($_REQUEST['url']),'/')), FILTER_SANITIZE_URL):'';

        if(!empty($url)){
            $urlSplit = explode('/', $url);
            if(count($urlSplit) > 0){
                $this->setController($urlSplit[0]);
                unset($urlSplit[0]);

                if(isset($urlSplit[1])){
                    $this->setMethod($urlSplit[1]);
                    unset($urlSplit[1]);

                    if(!empty($urlSplit)){
                        $this->setParams($urlSplit);
                    }
                }
            }
        }
    }

    protected function setController($controller='Home')
    {
        $controller = ucfirst($this->removeSpecialChar(trim($controller)));
        if(!empty($controller)){
            $this->controller = $controller;
        }

        return $this;
    }

    protected function setMethod($method='index')
    {
        $method = $this->removeSpecialChar(trim($method));

        if(!empty($method)){
            $this->method = $method;
        }

        return $this;
    }

    protected function setParams(array $arr)
    {
        if(!empty($arr)){
            $this->params = array_filter($arr);
        }

        return $this;
    }

    protected function onlyNumber($string)
    {
        return preg_replace("/[^0-9]/", "", $string);
    }

    protected function removeSpecialChar($string)
    {
        return preg_replace("/[^a-zA-Z0-9]/", "", $string);
    }

    protected function header($title='')
    {
        self::$title = $title;
        if(self::$authenticated) {
            self::setMenuDesktop('sistema/login/out', 'power_settings_new', '', 'Sair');
            self::setMenuDesktop('sistema/categoria', 'assignment', 'Categorias', 'Categoria de Produtos');
            self::setMenuDesktop('sistema/', 'store', 'Home', 'Página inicial');
        }

        require_once self::$publicoPath.'header.php';
    }

    protected function footer()
    {
        self::setJs(self::$basePath.'sistema/jQuery/sistema.js');
        require_once self::$publicoPath.'footer.php';
    }
}