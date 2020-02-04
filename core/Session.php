<?php

/*
    Sessionを管理するクラス。
    セッション開始、セット、取得を定義している。
*/

class Session
{
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    public function __construct()
    {
        //セッション開始 静的プロパティでチェックしている
        if(!self::$sessionStarted){
            session_start();

            self::$sessionStarted = true;
        }
    }

    public function set($name, $value)
    {

        $_SESSION[$name] = $value;
    }

    public function get($name, $default = null)
    {
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }

        return $default;
    }

    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    public function clear()
    {
        $_SESSION = array();
    }

    public function regenerate($destory = true)
    {
        //セッションIDを再発行する。
        //複数回呼ばれないように静的プロパティでチェックしている。
        if(!self::$sessionIdRegenerated){
            session_regenerate_id($destory);

            self::$sessionIdRegenerated = true;
        }
    }

    public function setAuthenticated($bool)
    {
        $this->set('_authenticated', (bool)$bool);

        //セッション固定攻撃への対策でregenerate
        $this->regenerate();
    }

    public function isAuthenticated()
    {
        return $this->get('_authenticated', false);
    }
}