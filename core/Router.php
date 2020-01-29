<?php

/*
    Routerクラス。ルーティング定義配列とPATH_INFOを受け取り、ルーティングパラメータを特定する。

*/
class Router
{
    protected $routes;

    public function __construct($definitions)
    {
        $this->routes = $this->compileRoutes($definitions);
    }

    //受け取ったルーティング定義配列中の動的パラメータ指定を正規表現で扱える形式に変換する
    public function compileRoutes($definitions)
    {
        $routes = array();

        foreach ($definitions as $url => $params) {
            //explode関数・・・左で右を分割
            //ltrim関数・・・左から右を取り除く
            $tokens = explode('/', ltrim($url, '/'));
            foreach ($tokens as $i => $token) {
                //strpos・・・左から右の位置を数字で返す（0スタート）
                if(0 === strpos($token, ':')){
                    $name = substr($token, 1);
                    $token = '(?p<' . $name . '>[^/]+)';
                }
                $tokens[$i] = $token;
            }
            //implode関数・・・左で右を連結する
            $pattern = '/' . implode('/', $tokens);
            $routes[$pattern] = $params;
        }

        return $routes;
    }

    //定義済みのルーティング定義配列とPATH_INFOのマッチングで、ルーティングパラメータの特定を行う。
    public function resolve($path_info)
    {
        if('/' !== substr($path_info, 0, 1)){
            $path_info = '/' . $path_info;
        }

        foreach ($this->routes as $pattern => $params) {
            if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
                $params = array_merge($params, $matches);

                return $params;
            }
        }

        return false;
    }
}