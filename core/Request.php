<?php

/*
    ユーザのリクエスト情報を制御するクラス。
    主な機能としては、HTTPメソッド（GET/POST)の判定、値の取得、URLの取得、フロントコントローラ採用のためURL情報の制御
*/

class Request
{
    public function isPost()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return true;
        }

        return false;
    }

    public function getGet($name, $default = null)
    {
        if(isset($_GET[$name]))
        {
            return $_GET[$name];
        }

        return $default;
    }

    public function getPost($name, $default = null)
    {
        if(isset($_POST[$name]))
        {
            return $_POST[$name];
        }

        return $default;
    }

    public function getHost()
    {
        if(!empty($_SERVER['HTTP_HOST']))
        {
            return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_NAME'];
    }

    public function isSsl()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        {
            return true;
        }

        return false;
    }

    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }


/*
    REQUEST_URI は、 ホスト部分よりあとの全ての値
    SCRIPT_NAME は、 フロントコントローラ（index.php)までの値
*/

    public function getBaseUrl()
    {
        $script_name = $_SERVER['SCRIPT_NAME'];

        $request_uri = $this->getRequestUri();

        if(0 === strpos($request_uri, $script_name)) //URLにindex.phpが含まれている場合(true)
        {
            return $script_name;
        }
        elseif (0 === strpos($request_uri, dirname($script_name))) 
        {
            return rtrim(dirname($script_name), '/'); 
        }

        return '';
    }

    public function getPathInfo()
    {
        $base_url = $this->getBaseUrl();
        $request_uri = $this->getRequestUri();

        if(false !== ($pos = strpos($request_uri, '?'))) //$request_uri に ? のパラメータが含まれていたら、それより前を$request_uriにする
        {
            $request_uri = substr($request_uri, 0, $pos);
        }

        $path_info = (string)substr($request_uri, strlen($base_url)); //$request_uri から $base_url を除いて、 $path_info に代入

        return $path_info;
    }
}