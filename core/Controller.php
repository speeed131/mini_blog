<?php

abstract class Controller
{
    protected $contoller_name;
    protected $action_name;
    protected $application;
    protected $request;
    protected $response;
    protected $session;
    protected $db_manager;

    public function __construct($application)
    {
        //下の処理で、UserControllerだったら、user の文字が返る。
        $this->contoller_name = strtolower(substr(get_class($this), 0, -10));

        $this->application = $application;
        $this->request = $application->getRequest();
        $this->response = $application->getResponse();
        $this->session = $application->getSesiion();
        $this->db_manager = $application->getDbManager();
    }

    public function run($action, $params = array())
    {
        $this->action_name = $action;

        $action_method = $action . 'Action';
        if (!method_exists($this, $action_method)) {
            $this->forward404();
        }

        $content = $this->action_method($params);

        return $content;
    }

    protected function render($variables = array(), $templete = null, $layout = 'layout')
    {
        $defaults = array(
            'request'  => $this->request,
            'base_url' => $this->request->getBaseUrl(),
            'session'  => $this->session,
        );

        $view = new View($this->application->getViewDir(), $defaults);

        if (is_null($templete)) {
            $templete = $this->action_name;
        }

        $path = $this->contoller_name . '/' .$templete;

        return $view->render($path, $variables, $layout);
    }
}