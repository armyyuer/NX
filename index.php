<?php

session_start();

require __DIR__.'/Public/AutoLoader.php';
require __DIR__.'/Application/Controller/Base.php';
require __DIR__.'/Application/Controller/BaseOnline.php';
require __DIR__.'/Application/View/View.php';
require __DIR__.'/Vendor/Smarty/Smarty.class.php';

define('ROOTPATH', __DIR__);

class Mi
{

    public function __construct()
    {
        spl_autoload_register([__CLASS__, 'loadClass']);
        $this->run();
    }

    private static function loadClass($class)
    {//echo $class."\n";
        if(count($_class = explode("\\", $class))>1){
            $class = end($_class);
            $models = __DIR__.'/Application/Model/' . $class . '.php';
            if (file_exists($models))include $models;
        }else{
            $controllers = __DIR__.'/Application/Controller/' . $class . '.php';
            if (file_exists($controllers))include $controllers;
        }
    }

    function run()
    {
        //$class = $_GET['class'] = isset($_GET['class']) && $_GET['class'] ? $_GET['class'] : 'Index';
        $class = $_GET['class'] = isset($_GET['class']) && $_GET['class'] ? $_GET['class'] : 'Member';
        $func = $_GET['func'] = isset($_GET['func']) && $_GET['func'] ? $_GET['func'] : 'Main';

        if(
        !is_file(
            $file = __DIR__.'/Application/Controller/'.$class.'.php'
        )
        )exit('控制器不存在');
        require $file;
        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
            new \Mi\View\View();
        }
        $mi = new $class();
        $mi->$func();
    }

}

new Mi();