<?php
namespace App;
use App;

/**
 * Ядро приложения
 * Class Core
 * @package App
 */
class Core
{

    public $defaultController = 'Home';
    public $defaultAction = "index";

    //MVC
    public $controller;
    public $action;
    public $params;

    /**
     * Обращаемся к роутеру и запускаем действие контроллера
     * кидаем исключение, если нет нужного контроллера или метода
     */
    public function run()
    {
        list($controller,$acion,$params) = App::$router->parserParamRequest();
        echo $this->runAction($controller, $acion, $params);
    }

    /**
     * Валидируем и назначаем контроллер
     * @param bool $controller
     * @return bool|string
     */
    public function validController($controller=FALSE){
        $this->controller = (!empty($controller)) ? ucfirst($controller) : $this->defaultController;

        //исключение если файа нет
        if($this->controller!=$this->defaultController && !file_exists(ROOTPATH.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.$this->controller.'.php')){
            $this->controller = $this->defaultController; //предполагаем что контроллер по умолчанию должен быть
        }

        //Даем namespace конроллеру (полное имя)
        $this->controller = "\\Controllers\\".ucfirst($this->controller);

        //исключение если класс не существует
        if($this->controller != $this->defaultController && !class_exists($this->controller)){
            $this->controller = $this->defaultController;
            $this->action = $this->defaultAction;
            $this->params = NULL;
        }

        return $this->controller;
    }

    /**
     * Валидируем и назначаем action
     * @param $controller
     * @param $action
     * @return mixed
     */
    public function validAction($controller,$action){
        $this->action = (empty($action)) ? $this->defaultAction : ucfirst($action);
        if (!method_exists($controller, $this->action)){
            $this->action = $this->defaultAction;
            $this->params = NULL;
        }
        return $this->action;
    }

    /**
     * Экстанцируем контроллер, выполняем метод с параметром
     * @param $controller
     * @param $action
     * @param $params
     * @return mixed
     */
    public function runAction($controller, $action=FALSE, $params=FALSE)
    {
        $controller = $this->validController($controller);
        $action     = $this->validAction($controller,$action);

        $obj = new $controller;
        return $obj->$action($params);

    }

}