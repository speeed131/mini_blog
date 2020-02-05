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
    protected $auth_actions = array();

    public function __construct($application)
    {
        //下の処理で、UserControllerだったら、user の文字が返る。
        $this->contoller_name = strtolower(substr(get_class($this), 0, -10));

        $this->application = $application;
        $this->request     = $application->getRequest();
        $this->response    = $application->getResponse();
        $this->session     = $application->getSesiion();
        $this->db_manager  = $application->getDbManager();
    }

    public function run($action, $params = array())
    {
        $this->action_name = $action;

        $action_method = $action . 'Action';
        if (!method_exists($this, $action_method)) {
            $this->forward404();
        }

        if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
            throw new UnauthorizedActionException();
        }

        $content = $this->$action_method($params);

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

    protected function forward404()
    {
        throw new HttpNotFoundException('Forwarded 404 page from ' . $this->contoller_name . '/' . $this->action_name);
    }

    protected function redirect($url)
    {
        // urlの書き換えが行われていた場合、再取得
        if (!preg_match('#https?://#', $url)) {
            $protocol = $this->request->isSsl() ? 'https://' : 'https://';
            $host     = $this->request->getHost();
            $base_url = $this->request->getBaseUrl();

            $url = $protocol . $host . $base_url . $url;
        }

        //redirectは302のステータスコード、headerはLocationにして自動的に302に書き換えてくれている
        $this->response->setStatusCode(302, 'Found');
        $this->response->setHttpHeader('Location', $url);
    }

    protected function generateCsrfToken($form_name)
    {
        $key = 'csrf_tokens/' . $form_name;
        //複数個のtokenを所持
        $tokens = $this->session->get($key, array());
        if (count($tokens) >= 10) {
            //１０個以上持っている場合古いものを削除
            array_shift($tokens);
        }
        
        //フォーム名にセッションIDとmicrotimeをつなげた値をハッシュ値として返している。
        $token = sha1($form_name . session_id() . microtime());
        $tokens[] = $token;

        $this->session->set($key, $tokens);

        return $token;
    }

    protected function checkCsrfToken($form_name, $token)
    {
        $key = 'csrf_tokens/' . $form_name;
        $tokens = $this->session->get($key, array());

        if(false !== ($pos = array_search($token, $tokens, true))){
            unset($token[$pos]);
            $this->session->set($key, $tokens);

            return true;
        }

        return false;
    }

    protected function needsAuthentication($action)
    {
        if($this->auth_actions === true
            || (is_array($this->auth_actions) && in_array($action, $this->auth_actions))
        )
        {
            return true;
        }

        return false;
    }
}